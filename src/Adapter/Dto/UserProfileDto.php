<?php

declare(strict_types=1);

namespace App\Adapter\Dto;

use App\Entity\Address;
use App\Entity\UserProfile;

class UserProfileDto
{
    public function __construct(
        readonly public int    $id,
        readonly public ?string $firstName = null,
        readonly public ?string $secondName = null,
        readonly public ?string  $phone = null,
        public ?string $birthday = null,
        public ?array $addresses = null,
    ) {
    }

    public static function fromEntity(UserProfile $userProfile): self
    {
        return new self(
            id: $userProfile->getId(),
            firstName: $userProfile->getFirstName(),
            secondName: $userProfile->getSecondName(),
            phone: $userProfile->getPhone(),
            birthday: $userProfile->getBirthDay()->format('Y-m-d'),
            addresses: array_map(
                static fn (Address $address): AddressDto =>
                new AddressDto(
                    id: $address->getId(),
                    city: $address->getCity(),
                    street: $address->getStreet(),
                    building: $address->getBuilding()
                ),
                $userProfile->getAddresses()->toArray()
            )
        );
    }

    public static function createEmpty(): self
    {
        return new self(0);
    }
}
