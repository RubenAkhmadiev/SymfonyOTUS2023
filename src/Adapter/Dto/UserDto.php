<?php

declare(strict_types=1);

namespace App\Adapter\Dto;

use App\Entity\User;
use DateTimeInterface;

class UserDto
{
    public function __construct(
        readonly public int    $id,
        readonly public string $email,
        readonly public ?int   $profileId
    ) {
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->getId(),
            email: $user->getEmail(),
            profileId: !empty($user->getProfile()) ? $user->getProfile()->getId() : null
        );
    }
}
