<?php

namespace App\GraphQL\Type\Object;

use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\Type\Object\User\UserProfileType;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class AddressType extends ObjectType
{
    public function __construct(
        private TypeRegistry $registry,
    ) {
        $config = TypeConfig::create()->withFields(

            Field::create('id', $this->registry->int()),

            Field::create('city', $this->registry->nullableString()),

            Field::create('street', $this->registry->nullableString()),

            Field::create('building', $this->registry->nullableString()),
        );

        parent::__construct($config->build());
    }
}
