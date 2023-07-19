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

class CancelOrder implements MutationInterface
{
    public function __construct(
        private TypeRegistry $registry,
        private CurrentUser $currentUser,
        private UserRepository $userRepository,
        private CustomerAdapter $customerAdapter,
    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->string())
            ->withDescription('Создание заказа')
            ->withArguments(
                Argument::create('orderId', $this->registry->int())->withDescription('ID заказа')
            )
            ->withResolver(
                function (mixed $root, array $args): int {
                    if (!$this->currentUser->isAuthorized()) {
                        throw ClientAwareException::createAccessDenied();
                    }

                    $user = $this->userRepository->find($this->currentUser->getUserId());
                    if ($user === null) {
                        throw new UserNotFoundException('Данный пользователь не найден');
                    }

                    $orderId = $args['orderId'];

                    $this->customerAdapter->cancelOrder($user, $orderId);

                    return "Заказ отменён";
                }
            )
            ->build();
    }
}
