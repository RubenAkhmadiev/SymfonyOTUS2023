<?php

namespace App\Backoffice\Enum;

enum PartnerTypeEnum
{
    case RESTAURANT;
    case SUPERMARKET;

    public static function create(string $partnerType): self
    {
        return match ($partnerType) {
            'restaurant'  => self::RESTAURANT,
            'supermarket' => self::SUPERMARKET,
        };
    }

    public function getName(): string
    {
        return match ($this) {
            self::RESTAURANT  => 'restaurant',
            self::SUPERMARKET => 'supermarket',
        };
    }
}
