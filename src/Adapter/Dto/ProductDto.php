<?php

declare(strict_types=1);

namespace App\Adapter\Dto;

use App\Backoffice\Entity\Category as CategoryEntity;
use App\Backoffice\Entity\Product as ProductEntity;

class ProductDto
{
    public function __construct(
        readonly public int     $id,
        readonly public string  $title,
        readonly public ?string $description,
        readonly public float   $price,
        /** @var array<CategoryDto> $categories */
        readonly public array   $categories,
    ) {
    }

    public static function fromEntity(ProductEntity $productEntity): self
    {
        return new self(
            id: $productEntity->getId(),
            title: $productEntity->getTitle(),
            description: $productEntity->getDescription(),
            price: $productEntity->getPrice(),
            categories: array_map(
                static fn(CategoryEntity $categoryEntity) => CategoryDto::fromEntity($categoryEntity),
                $productEntity->getCategories()->toArray(),
            ),
        );
    }
}
