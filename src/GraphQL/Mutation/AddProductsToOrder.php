<?php

namespace App\GraphQL\Mutation;

use App\Adapter\CustomerAdapter;
use App\ApiUser\CurrentUser;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class AddProductsToOrder implements MutationInterface
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly CurrentUser $currentUser,
        private readonly UserRepository $userRepository,
        private readonly CustomerAdapter $customerAdapter
    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->string())
            ->withDescription('Добавление продуктов в заказ')
            ->withArguments(
                Argument::create('orderId', $this->registry->int())->withDescription('ID заказа'),
                Argument::create('productIds', $this->registry->listOf($this->registry->int()))
                    ->withDescription('ID продуктов'),

            )
            ->withResolver(
                function (mixed $root, array $args): string {

                    if (!$this->currentUser->isAuthorized()) {
                        throw ClientAwareException::createAccessDenied();
                    }

                    $user = $this->userRepository->find($this->currentUser->getUserId());
                    if ($user === null) {
                        throw new UserNotFoundException('Данный пользователь не найден');
                    }

                    $orderId = $args['orderId'];
                    $productIds = $args['productIds'];

                    $this->customerAdapter->addProductsToOrder($user, $orderId, $productIds);

                    return "Продукты добавлены в заказ";
                }
            )
            ->build();
    }
}
