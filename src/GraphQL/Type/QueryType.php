<?php

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use SimPod\GraphQLUtils\Builder\FieldBuilder;

final class QueryType extends ObjectType
{
    public function __construct(
        private readonly TypeRegistry $registry
    ) {
        $config = [
            'fields' => fn() => [

                FieldBuilder::create('viewer', $this->registry->string())->build(),

                FieldBuilder::create('second', $this->registry->int())->build(),

            ],
        ];

        parent::__construct($config);
    }
}
