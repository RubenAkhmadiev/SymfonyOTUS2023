<?php

namespace App\GraphQL\Type\Object;

use App\ApiUser\CurrentUser;
use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\Type\Dto\UserProfileDto;
use App\GraphQL\Type\Object\User\UserProfileType;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class CurrentUserType extends ObjectType
{
    public function __construct(
        private TypeRegistry $registry,
    ) {
        $profileResolver = fn(CurrentUser $user): UserProfileDto => $user->getProfile();

        $config = TypeConfig::create()->withFields(

            Field::create('profile', $this->registry->type(UserProfileType::class))
                ->withResolver($profileResolver),
        );

        parent::__construct($config->build());
    }
}
