<?php

namespace App\Customer\Service;

use App\Adapter\Dto\UserDto;
use App\Backoffice\Repository\ProductRepository;
use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatusEnum;
use App\Repository\OrderRepository;
use App\Telegram\Controller\Dto\OrderPaymentDto;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class OrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserService $userService,
        private readonly OrderRepository $orderRepository,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function createOrder(UserDto $userDto, OrderPaymentDto $orderPaymentDto): ?int
    {
        $conn = $this->entityManager->getConnection();
        $user = $this->userService->getUser($userDto->id);

        $order = new Order();
        $order->setUser($user);
        $order->setNumber(random_int(10, 100));
        $order->setSum($orderPaymentDto->sum);
        $order->setCreationDate(new DateTime());
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        foreach ($orderPaymentDto->itemIds as $itemId) {
            $sql = <<<SQL
            insert into public.item_order (item_id, order_id)
            values (:item_id, :order_id)
        SQL;

            $conn->executeQuery($sql, ['item_id' => $itemId, 'order_id' => $order->getId()]);
        }

        return $order->getId();
    }

    public function addProductsToOrder(User $user, int $orderId, array $productIds): void
    {
        $order = $this->orderRepository->findOneBy(
            [
                'id' => $orderId,
                'user' => $user->getId()
            ]
        );

        if ($order === null) {
            throw new \Exception('Заказ не найден');
        }

        $products = $this->productRepository->findBy(['id' => $productIds]);

        if (!empty($products)) {
            foreach ($products as $product) {
                $order->addProduct($product);
            }

            $order->setStatus(OrderStatusEnum::PROCESS);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }
    }

    public function cancelOrder(User $user, int $orderId): void
    {
        $order = $this->orderRepository->findOneBy(
            [
                'id' => $orderId,
                'user' => $user->getId()
            ]
        );

        if ($order === null) {
            throw new \Exception('Заказ не найден');
        }

        $order->setStatus(OrderStatusEnum::CANCELED);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    public function userOrders(int $userId): ?array
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        /* @var $user User */
        $user = $userRepository->find($userId);

        return $user->getOrders()->toArray();
    }
}
