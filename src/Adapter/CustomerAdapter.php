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

    public function createOrUpdateUser(OrderPaymentDto $requestDto): User
    {
        return $this->userService->createOrUpdateUser(
                $requestDto->telegramId,
                $requestDto->email,
                $requestDto->name,
                $requestDto->sername,
                $requestDto->phone,
                $requestDto->city,
                $requestDto->street,
                $requestDto->building
        );
    }


    public function createOrder(User $user, OrderPaymentDto $orderPaymentDto): ?int
    {
        return $this->orderService->createOrder($user, $orderPaymentDto);
    }

    public function userOrders(int $userId): ?array
    {
        return $this->orderService->userOrders($userId);
    }
}
