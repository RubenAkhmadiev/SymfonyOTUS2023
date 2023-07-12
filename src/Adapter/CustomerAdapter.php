<?php

namespace App\Adapter;

use App\Controller\Telegram\Dto\OrderPaymentDto;
use App\Entity\User;
use App\GraphQL\Service\ItemService;
use App\GraphQL\Service\OrderService;
use App\GraphQL\Service\UserService;

class CustomerAdapter
{
    public function __construct(
        protected ItemService $itemService,
        protected OrderService $orderService,
        protected UserService $userService,
    ) {
    }


    public function getItems(int $page, int $perPage): array
    {
        return $this->itemService->getItems($page, $perPage);
    }

    public function createOrUpdateUser(int $telegramId, string $firstName, string $secondName, string $phone, string $address): User
    {
        return $this->createOrUpdateUser($telegramId, $firstName, $secondName, $phone, $address);
    }


    public function createOrder(OrderPaymentDto $orderPaymentDto): ?int
    {
        return $this->orderService->createOrder($orderPaymentDto);
    }

    public function userOrders(int $userId): ?array
    {
        return $this->orderService->userOrders($userId);
    }
}
