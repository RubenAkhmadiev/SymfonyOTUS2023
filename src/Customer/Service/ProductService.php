<?php

namespace App\Customer\Service;

use App\Backoffice\Entity\Product;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getProducts(int $page, int $limit): ?array
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        return $productRepository->findBy(
            criteria: [],
            limit: $limit + 1,
            offset: $limit * $page
        );
    }
}
