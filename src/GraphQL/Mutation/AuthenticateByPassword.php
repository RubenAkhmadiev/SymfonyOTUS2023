<?php

namespace App\GraphQL\Mutation;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\Type;

class AuthenticateByPassword extends Mutation
{
    public function __construct(
        private readonly TypeRegistry $registry
    ) {
    }

    public function getName(): string
    {
        return 'authenticateByPassword';
    }

    public function getDescription(): ?string
    {
        return 'Аутентификация пользователя по логину и паролю';
    }

    public function getType(): Type
    {
        return $this->registry->string();
    }

    public function getArgs(): array
    {
        return [
            'login' => [
                'type' => $this->registry->string(),
            ],
            'password' => [
                'type' => $this->registry->string(),
            ],
        ];
    }

    public function getResolve(): callable
    {
        return function () {
            return '';
        };
    }
}
