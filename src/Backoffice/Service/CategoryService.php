<?php

namespace App\Backoffice\Service;

use App\Backoffice\Entity\Category;
use App\Backoffice\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(
        protected EntityManagerInterface $em,
    ) {
        $this->categoryRepository = $em->getRepository(Category::class);
    }

    /**
     * @return array{has_more: bool, items: Category[]}
     */
    public function getAll(int $limit, int $page): array
    {
        $items = $this->categoryRepository->findBy(
            criteria: [],
            limit: $limit + 1,
            offset: $limit * $page,
        );

        return [
            'has_more' => count($items) > $limit,
            'items'    => array_slice($items, 0, $limit),
        ];
    }

    public function getById(int $id)  {
        $item = $this->categoryRepository->find($id);

        if ($item === null) {
            throw new \Exception("Not found");
        }

        return $item;
    }
}
