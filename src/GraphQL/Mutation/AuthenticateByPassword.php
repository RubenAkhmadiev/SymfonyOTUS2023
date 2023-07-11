<?php

namespace App\GraphQL\Mutation;

use App\GraphQL\Error\CategoryEnum;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use App\Security\TokenManager;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class AuthenticateByPassword implements MutationInterface
{
    public function __construct(
        private TypeRegistry $registry,
        private TokenManager $tokenManager,
    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->string())
            ->withDescription('Аутентификация пользователя по email и паролю')
            ->withArguments(
                Argument::create('email', $this->registry->string()),
                Argument::create('password', $this->registry->string()),
            )
            ->withResolver(
                function (mixed $root, array $args): string {
                    try {
                        $userId = 2;

                        if (null === $userId) {
                            throw new ClientAwareException(
                                CategoryEnum::INVALID_ARGUMENT(),
                                'Указано неверное имя пользователя или пароль'
                            );
                        }
                    } catch (ValidationFailedException $e) {
                        throw ClientAwareException::createFromValidationFailedException($e);
                    }

                    return $this->tokenManager->createToken($userId);
                }
            )
            ->build();
    }
}
