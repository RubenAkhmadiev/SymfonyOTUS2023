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
        ];

        parent::__construct(
            [
                'fields' => static fn() => array_combine(
                    array_map(static fn(Mutation\MutationInterface $m) => $m->getName(), $mutations),
                    array_map(static fn(Mutation\MutationInterface $m) => $m->build(), $mutations)
                ),
            ]
        );
    }
}
