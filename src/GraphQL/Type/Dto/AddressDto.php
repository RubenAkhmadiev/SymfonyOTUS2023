<?php

namespace App\GraphQL\Type\Dto;

final class AddressDto implements TypeDtoInterface
{
    public function __construct(
        public int $id,
        public ?string $city = null,
        public ?string $street = null,
        public ?string $building = null,
    ) {
    }
}
