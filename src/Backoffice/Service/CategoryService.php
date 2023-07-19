<?php

namespace App\Backoffice\Service;

use App\Backoffice\Entity\Category;
use App\Backoffice\Repository\CategoryRepository;
use App\Exception\NotFoundException;
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

    /**
     * @throws NotFoundException
     */
    public function getDetail(int $categoryId): Category
    {
        $category = $this->categoryRepository->find($categoryId);

        if (is_null($category)) {
            throw new NotFoundException();
        }

        return $category;
    }

    public function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setName($name);
        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    /**
     * @throws NotFoundException
     */
    public function updateCategory(int $categoryId, string $name): Category
    {
        $category = $this->categoryRepository->find($categoryId);

        if (is_null($category)) {
            throw new NotFoundException();
        }

        $category->setName($name);
        $this->em->flush();

        return $category;
    }

    public function getById(int $id)  {
        $item = $this->categoryRepository->find($id);

        if ($item === null) {
            throw new \Exception("Not found");
        }

        return $item;
    }
}
