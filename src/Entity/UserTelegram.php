<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Repository\UserTelegramRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTelegramRepository::class)]
#[ORM\Table(name: '`user_telegram`')]
class UserTelegram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $user_id;

    #[ORM\Column]
    private int $telegram_id;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTelegramId(): int
    {
        return $this->telegram_id;
    }

    public function setTelegramId(int $telegramId): static
    {
        $this->telegram_id = $telegramId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): static
    {
        $this->user_id = $userId;

        return $this;
    }
}
