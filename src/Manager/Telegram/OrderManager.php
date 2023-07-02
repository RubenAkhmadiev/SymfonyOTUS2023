<?php

namespace App\Manager\Telegram;

use App\Controller\Telegram\Dto\OrderPaymentDto;
use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class OrderManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserManager $userManager,
    ) {
    }

    public function createOrder(OrderPaymentDto $orderPaymentDto): ?int
    {
        $user = $this->userManager->createOrUpdateUser(
            $orderPaymentDto->telegramId,
            $orderPaymentDto->firstName,
            $orderPaymentDto->secondName,
            $orderPaymentDto->phone,
            $orderPaymentDto->address
        );

        $order = new Order();
        $order->setUserId($user);
        $order->setNumber(213);
        $order->setSum($orderPaymentDto->sum);
        $order->setCreationDate(new DateTime());
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order->getId();
    }

    public function userOrders(int $userId): ?array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        $arrayOrders = [];
        foreach ($user->getOrders() as $order) {
            $arrayOrders[] = $order->toArray();
        }

        return $arrayOrders;
    }
}
