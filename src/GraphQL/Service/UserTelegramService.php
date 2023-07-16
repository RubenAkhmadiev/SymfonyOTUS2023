<?php

namespace App\GraphQL\Service;

use App\Entity\UserTelegram;
use Doctrine\ORM\EntityManagerInterface;

class UserTelegramService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createRelation(int $userId, ?int $telegramId): void
    {
        if ($telegramId) {
            $userTelegram = new UserTelegram();
            $userTelegram->setUserId($userId);
            $userTelegram->setTelegramId($telegramId);
            $this->entityManager->persist($userTelegram);
            $this->entityManager->flush();
        }
    }
}
