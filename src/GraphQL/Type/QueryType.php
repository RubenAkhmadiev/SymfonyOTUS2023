<?php

namespace App\GraphQL\Type;

use App\ApiUser\CurrentUser;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\Type\Object\CurrentUserType;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

final class QueryType extends ObjectType
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly CurrentUser $currentUser,
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
                ),

            Field::create('currentUser', $this->registry->nullableType(CurrentUserType::class))
                ->withResolver(
                    function (): CurrentUser {
                        if (!$this->currentUser->isAuthorized()) {
                            throw ClientAwareException::createAccessDenied();
                        }

                        return $this->currentUser;
                    }
                ),
        );

        parent::__construct($config->build());
    }
}
