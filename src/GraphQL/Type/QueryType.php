<?php

namespace App\GraphQL\Type;

use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use SimPod\GraphQLUtils\Builder\FieldBuilder;

final class QueryType extends ObjectType
{
    public function __construct(
        private readonly TypeRegistry $registry,
    ) {
        $config = TypeConfig::create()->withFields(

                Field::create('echo', $this->registry->string())
                    ->withArguments(
                        Argument::create('message', $this->registry->string())
                            ->withDescription('Тестовое сообщение')
                    )
                    ->withResolver(
                        function (mixed $root, array $args): string {
                            return $args['message'];
                        }
                    )
        );

        parent::__construct($config->build());
    }
}
