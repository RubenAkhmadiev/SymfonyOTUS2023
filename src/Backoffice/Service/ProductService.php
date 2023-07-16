<?php

namespace App\Backoffice\Service;

use App\Backoffice\Entity\Product;
use App\Backoffice\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(
        protected EntityManagerInterface $em,
    ) {
        $this->productRepository = $em->getRepository(Product::class);
    }

    /**
     * @return array{has_more: bool, items: Product[]}
     */
    public function getAll(int $limit, int $page): array
    {
        $items = $this->productRepository->findBy(
            criteria: [],
            limit: $limit + 1,
            offset: $limit * $page,
        );

        return [
            'has_more' => count($items) > $limit,
            'items'    => array_slice($items, 0, $limit),
        ];
    }
}
