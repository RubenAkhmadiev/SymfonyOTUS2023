<?php

declare(strict_types=1);

namespace App\Adapter\Dto;

use App\Backoffice\Entity\Partner as PartnerEntity;

class ProductDto
{
    public function __construct(
        readonly public int    $id,
        readonly public string $name,
        readonly public string $type,
    ) {
    }

    public static function fromEntity(PartnerEntity $partnerEntity): self
    {
        return new self(
            id: $partnerEntity->getId(),
            name: $partnerEntity->getName(),
            type: $partnerEntity->getType()->getName(),
        );
    }
}
