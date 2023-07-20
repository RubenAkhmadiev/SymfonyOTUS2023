<?php

namespace App\GraphQL\Type;

use App\GraphQL\Mutation;
use GraphQL\Type\Definition\ObjectType;
use Psr\Container\ContainerInterface;

final class MutationType extends ObjectType
{
    public function __construct(ContainerInterface $container)
    {
        // Общий список мутаций
        $mutations = [
            $container->get(Mutation\AuthenticateByPassword::class),
            $container->get(Mutation\Logout::class),
            $container->get(Mutation\CreateUserProfile::class),
            $container->get(Mutation\CreateUserProfileAddress::class),
            $container->get(Mutation\CreateUser::class),
            $container->get(Mutation\CreateOrder::class),
            $container->get(Mutation\AddProductsToOrder::class),
            $container->get(Mutation\CancelOrder::class),
            $container->get(Mutation\UpdateUserProfile::class),
            $container->get(Mutation\UpdateUserProfileAddress::class),
        ];

        $fields = array_combine(
            array_map(static fn(Mutation\MutationInterface $m) => lcfirst((new \ReflectionClass($m))->getShortName()), $mutations),
            array_map(static fn(Mutation\MutationInterface $m) => $m->build(), $mutations)
        );

        ksort($fields);

        parent::__construct(['fields' => $fields]);
    }
}
