<?php

namespace App\GraphQL\Type\Object;

use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class ProductType extends ObjectType
{
    public function __construct(
        private TypeRegistry $registry,
    ) {
        $config = TypeConfig::create()->withFields(

            Field::create('id', $this->registry->int()),

            Field::create('name', $this->registry->nullableString()),

            Field::create('price', $this->registry->nullableFloat()),
        );

        parent::__construct($config->build());
    }
}
