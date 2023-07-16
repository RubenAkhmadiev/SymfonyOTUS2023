<?php

namespace App\Adapter\Dto;

use App\Backoffice\Entity\Category as CategoryEntity;

class CategoryDto
{
    public function __construct(
        readonly public int    $id,
        readonly public string $name,
    ) {
    }

    public static function fromEntity(CategoryEntity $categoryEntity): self
    {
        return new self(
            id: $categoryEntity->getId(),
            name: $categoryEntity->getName(),
        );
    }
}
