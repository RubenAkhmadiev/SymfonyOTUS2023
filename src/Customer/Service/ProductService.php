<?php

namespace App\Customer\Service;

use App\Backoffice\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getProducts(int $page, int $limit): array
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $productsCollection = $productRepository->findBy(
            criteria: [],
            limit: $limit + 1,
            offset: $limit * $page
        );

        $products = [];
        foreach ($productsCollection as $product) {
            $products[] = $product->toArray();
        }

        return $products;
    }
}
