<?php

namespace App\GraphQL\Type\Object;

use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\Type\Object\User\UserProfileType;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class OrderType extends ObjectType
{
    public function __construct(
        private TypeRegistry $registry,
    ) {
        $config = TypeConfig::create()->withFields(

            Field::create('id', $this->registry->int()),

            Field::create('userId', $this->registry->nullableInt()),

            Field::create('products', $this->registry->nullableListOf($this->registry->type(ProductType::class))),

            Field::create('number', $this->registry->nullableString()),

            Field::create('status', $this->registry->nullableString()),

            Field::create('creationDate', $this->registry->nullableString()),

            Field::create('sum', $this->registry->nullableFloat()),
        );

        parent::__construct($config->build());
    }
}
