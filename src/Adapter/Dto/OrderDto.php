<?php

declare(strict_types=1);

namespace App\Adapter\Dto;

use App\Backoffice\Entity\Product;
use App\Entity\Order;

class OrderDto
{
    public function __construct(
        readonly public int $id,
        readonly public ?int $userId,
        readonly public ?string $number,
        readonly public ?string $creationDate,
        readonly public ?float $sum = null,
        readonly public ?array $products = []
    ) {
    }

    public static function fromEntity(Order $order): self
    {
        return new self(
            id: $order->getId(),
            userId: $order->getUser()->getId(),
            number: $order->getNumber(),
            creationDate: $order->getCreationDate()->format('Y-m-d H:i:s'),
            sum: $order->getSum(),
            products: array_map(
                static fn(Product $product) => ProductDto::fromEntity($product),
                $order->getProducts()->toArray(),
            ),
        );
    }
}
