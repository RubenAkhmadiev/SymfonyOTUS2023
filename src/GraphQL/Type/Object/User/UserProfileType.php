<?php

namespace App\GraphQL\Type\Object\User;

use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\Type\Object\AddressType;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class UserProfileType extends ObjectType
{
    public function __construct(
        private TypeRegistry $registry,
    ) {
        $config = TypeConfig::create()->withFields(

            Field::create('id', $this->registry->int()),

            Field::create('firstName', $this->registry->nullableString()),

            Field::create('secondName', $this->registry->nullableString()),

            Field::create('phone', $this->registry->nullableString()),

            Field::create('birthday', $this->registry->nullableString()),

            Field::create('addresses', $this->registry->nullableListOf($this->registry->type(AddressType::class))),
        );

        parent::__construct($config->build());
    }
}
