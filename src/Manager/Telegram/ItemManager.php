<?php

namespace App\Manager\Telegram;

use App\Entity\Item;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class ItemManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getItems(int $page, int $perPage): array
    {
        $itemRepository = $this->entityManager->getRepository(Item::class);
        $itemsCollection = $itemRepository->getItems($page, $perPage);

        $items = [];
        foreach ($itemsCollection as $item) {
            $items[] = $item->toArray();
        }

        return $items;
    }
}
