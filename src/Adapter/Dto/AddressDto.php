<?php

namespace App\Adapter\Dto;

use App\Entity\Address;

class AddressDto
{
    public function __construct(
        public int $id,
        public ?string $city = null,
        public ?string $street = null,
        public ?string $building = null,
    ) {
    }

    public static function fromEntity(Address $address): self
    {
        return new self(
            id: $address->getId(),
            city: $address->getCity(),
            street: $address->getStreet(),
            building: $address->getBuilding(),
        );
    }
}
