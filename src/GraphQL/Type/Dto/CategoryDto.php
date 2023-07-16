<?php

namespace App\GraphQL\Type\Dto;


use App\Backoffice\Entity\Category;

final class CategoryDto implements TypeDtoInterface
{
    public function __construct(
        public int $id,
        public ?string $name = null,
    ) {
    }

    public static function fromEntity(Category $category): self
    {
        return new self(
            id: $category->getId(),
            name: $category->getName(),
        );
    }
}
