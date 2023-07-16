<?php

namespace App\GraphQL\Type\Dto;

final class ItemDto implements TypeDtoInterface
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?float $price = null,
    ) {
    }
}
