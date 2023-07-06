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

            Field::create('profile', $this->registry->type(UserProfileType::class)),

            Field::create('city', $this->registry->string()),

            Field::create('street', $this->registry->string()),

            Field::create('building', $this->registry->string()),
        );

        parent::__construct($config->build());
    }
}
