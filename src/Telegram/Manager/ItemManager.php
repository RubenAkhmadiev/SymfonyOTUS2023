<?php

namespace App\Telegram\Manager;

use App\BackOffice\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ItemManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getItems(int $page, int $perPage): array
    {
        $itemRepository = $this->entityManager->getRepository(Product::class);
        $itemsCollection = $itemRepository->getItems($page, $perPage);

        $items = [];
        foreach ($itemsCollection as $item) {
            $items[] = $item->toArray();
        }

        return $items;
    }
}
