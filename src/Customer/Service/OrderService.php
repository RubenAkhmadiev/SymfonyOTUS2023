<?php

namespace App\Customer\Service;

use App\Adapter\Dto\UserDto;
use App\Entity\Order;
use App\Entity\User;
use App\Telegram\Controller\Dto\OrderPaymentDto;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class OrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserService $userService
    ) {
    }

    public function createOrder(UserDto $user, OrderPaymentDto $orderPaymentDto): ?int
    {
        $conn = $this->entityManager->getConnection();
        $user = $this->userService->getUser($user->id);

        $order = new Order();
        $order->setUserId($user);
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

    public function userOrders(int $userId): ?array
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        /* @var $user User */
        $user = $userRepository->find($userId);

        return $user->getOrders()->toArray();
    }
}
