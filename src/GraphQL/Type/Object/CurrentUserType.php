<?php

namespace App\GraphQL\Type\Object;

use App\Adapter\CustomerAdapter;
use App\Adapter\Dto\OrderDto;
use App\Adapter\Dto\UserProfileDto;
use App\ApiUser\CurrentUser;
use App\Entity\Order;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\Type\Object\User\UserProfileType;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class CurrentUserType extends ObjectType
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly CustomerAdapter $adapter,
        private readonly CurrentUser $currentUser,

    ) {
        $profileResolver = fn(CurrentUser $user): UserProfileDto => $user->getProfile();

        $config = TypeConfig::create()->withFields(

            Field::create('profile', $this->registry->type(UserProfileType::class))
                ->withResolver($profileResolver),

            Field::create('orders', $this->registry->nullableListOf($this->registry->type(OrderType::class)))
                ->withResolver(
                    function (CurrentUser $user): array {
                        if (!$this->currentUser->isAuthorized()) {
                            throw ClientAwareException::createAccessDenied();
                        }

                        $orders = $this->adapter->userOrders($user->getUserId());

                        $ordersDto =  array_map(
                            static fn(Order $order) => OrderDto::fromEntity($order),
                            $orders,
                        );

                        $t = 1;

                        return $ordersDto;
                    }
                )
        );

        parent::__construct($config->build());
    }
}
