<?php

namespace App\Adapter;

use App\Adapter\Dto\UserDto;

use App\Backoffice\Service\CategoryService;
use App\Customer\Service\AddressService;
use App\Customer\Service\UserProfileService;
use App\Customer\Service\ProductService;
use App\Customer\Service\OrderService;
use App\Customer\Service\UserService;
use App\Entity\User;
use App\Telegram\Controller\Dto\OrderPaymentDto;

class CustomerAdapter
{
    public function __construct(
        protected ProductService $productService,
        protected OrderService $orderService,
        protected CategoryService $categoryService,
        protected UserService $userService,
        protected UserProfileService $userProfileService,
        protected AddressService $addressService,
    ) {
    }


    public function getProducts(int $page, int $perPage): array
    {
        return $this->productService->getProducts($page, $perPage);
    }

    public function getCategories(int $page, int $perPage): array
    {
        return $this->categoryService->getAll($page, $perPage);
    }

    public function checkExistsUser(?int $telegramId): ?int
    {
        return $this->userService->checkExistsUser($telegramId);
    }


    public function createOrder(UserDto $user, OrderPaymentDto $orderPaymentDto): ?int
    {
        return $this->orderService->createOrder($user, $orderPaymentDto);
    }

    public function userOrders(int $userId): ?array
    {
        return $this->orderService->userOrders($userId);
    }

    public function createUser(OrderPaymentDto $requestDto): ?UserDto
    {
        $user = $this->userService->createUser($requestDto->email, 'nothing');

        $userProfile = $this->userProfileService->createUserProfile(
            $user,
            $requestDto->name,
            $requestDto->sername,
            $requestDto->phone,
        );

        $this->addressService->createAddress(
            $userProfile->getId(),
            $requestDto->city,
            $requestDto->street,
            $requestDto->building,
        );

        return UserDto::fromEntity($user);
    }

    public function updateUser(?int $userId, OrderPaymentDto $requestDto): ?UserDto
    {
        $user = $this->userService->updateUser($userId, $requestDto->email, 'nothing');

        $userProfile = $this->userProfileService->updateUserProfile(
            $user->getId(),
            $requestDto->name,
            $requestDto->sername,
            $requestDto->phone,
        );

        $this->addressService->updateAddress(
            $userProfile->getId(),
            $requestDto->city,
            $requestDto->street,
            $requestDto->building,
        );

        return UserDto::fromEntity($user);
    }

    public function addProductsToOrder(User $user, int $orderId, array $productIds): void
    {
        $this->orderService->addProductsToOrder($user, $orderId, $productIds);
    }

    public function cancelOrder(User $user, int $orderId): void
    {
        $this->orderService->addProductsToOrder($user, $orderId, $productIds);
    }
}
