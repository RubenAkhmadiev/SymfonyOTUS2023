<?php

declare(strict_types=1);

namespace App\Adapter\Dto;

use App\Entity\UserProfile;

class UserProfileDto
{
    public function __construct(
        readonly public int    $id,
        readonly public ?string $firstName,
        readonly public ?string $secondName,
        readonly public ?string    $phone
    ) {
    }

    public static function fromEntity(UserProfile $userProfile): self
    {
        return new self(
            id: $userProfile->getId(),
            firstName: $userProfile->getFirstName(),
            secondName: $userProfile->getSecondName(),
            phone: $userProfile->getPhone(),
        );
    }
}
