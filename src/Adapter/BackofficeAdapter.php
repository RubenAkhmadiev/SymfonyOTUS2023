<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Adapter\Dto\CategoryDto;
use App\Adapter\Dto\PartnerDto;
use App\Adapter\Dto\ProductDto;
use App\Backoffice\Entity\Category as CategoryEntity;
use App\Backoffice\Entity\Partner as PartnerEntity;
use App\Backoffice\Entity\Product as ProductEntity;
use App\Backoffice\Service\CategoryService;
use App\Backoffice\Service\PartnerService;
use App\Backoffice\Service\ProductService;

final class BackofficeAdapter
{
    public function __construct(
        protected CategoryService $categoryService,
        protected PartnerService  $partnerService,
        protected ProductService  $productService,
    ) {
    }

    /**
     * @return array{has_more: bool, items: CategoryDto[]}
     */
    public function getCategories(
        int $limit = 20,
        int $page = 0,
        array $filters = [],
        array $orderBy = [],
    ): array {
        $result = $this->categoryService->getAll(
            limit: $limit,
            page: $page,
        );

        return [
            'has_more' => $result['has_more'],
            'items'    => array_map(
                static fn(CategoryEntity $categoryEntity) => CategoryDto::fromEntity($categoryEntity),
                $result['items'],
            ),
        ];
    }

    public function getCategoryById(int $id): CategoryDto
    {
        $categoryEntity = $this->categoryService->getDetail(categoryId: $id);

        return CategoryDto::fromEntity($categoryEntity);
    }

    /**
     * @return array{has_more: bool, items: PartnerDto[]}
     */
    public function getPartners(
        int $limit = 20,
        int $page = 0,
        array $filters = [],
        array $orderBy = [],
    ): array {
        $result = $this->partnerService->getAll(
            limit: $limit,
            page: $page,
        );

        return [
            'has_more' => $result['has_more'],
            'items'    => array_map(
                static fn(PartnerEntity $partnerEntity) => PartnerDto::fromEntity($partnerEntity),
                $result['items'],
            ),
        ];
    }

    /**
     * @return array{has_more: bool, items: ProductDto[]}
     */
    public function getProducts(
        int $limit = 20,
        int $page = 0,
        array $filters = [],
        array $orderBy = [],
    ): array {
        $result = $this->productService->getAll(
            limit: $limit,
            page: $page,
        );

        return [
            'has_more' => $result['has_more'],
            'items'    => array_map(
                static fn(ProductEntity $productEntity) => ProductDto::fromEntity($productEntity),
                $result['items'],
            ),
        ];
    }
}
