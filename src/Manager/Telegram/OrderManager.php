<?php

namespace App\Manager\Telegram;

use App\Controller\Telegram\Dto\OrderPaymentDto;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createOrder(OrderPaymentDto $orderPaymentDto): ?int
    {
        $user = $this->createOrUpdateUser(
            $orderPaymentDto->userId,
            $orderPaymentDto->firstName,
            $orderPaymentDto->secondName,
            $orderPaymentDto->phone,
            $orderPaymentDto->address
        );

        $order = new Order();
        $order->setUserId($user);
        $order->setSum($orderPaymentDto->sum);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order->getId();
    }

    public function createOrUpdateUser(
        int $userId, string $firstName, string $secondName, string $phone, string $address
    ): User
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $userProfileRepository = $this->entityManager->getRepository(UserProfile::class);

        $user = $userRepository->find($userId);

        if (!$user) {
            $user = new User();
            $user->setId($userId);
        }

        $userProfile = $userProfileRepository->find($user->getProfile()->getId());
        if (!$userProfile) {
            $userProfile = new UserProfile();
        }

        $userProfile->setFirstName($firstName);
        $userProfile->setSecondName($secondName);
        $userProfile->setPhone($phone);
        $userProfile->setAddresses([$address]);
        $userProfile->setPhone($phone);

        $user->setLogin($firstName);
        $user->setAd($name);
        $user->setLogin($name);
        $user->setLogin($name);

        return $user;
    }
}
