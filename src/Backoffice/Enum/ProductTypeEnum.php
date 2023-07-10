<?php

namespace App\Backoffice\Enum;

enum ProductTypeEnum
{
    case DISH;

    public function create(string $productType): self
    {
        return match ($productType) {
            'dish' => self::DISH,
        };
    }
}
