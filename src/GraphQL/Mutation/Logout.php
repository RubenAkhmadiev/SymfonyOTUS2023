<?php

namespace App\GraphQL\Mutation;

use App\ApiUser\CurrentUser;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use App\Security\TokenManager;

class Logout implements MutationInterface
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly TokenManager $tokenManager,
        private readonly CurrentUser $currentUser,
    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->null())
            ->withDescription('Инвалидация текущего токена доступа к API')
            ->withResolver(
                function (): void {
                    if (!$this->currentUser->isAuthorized()) {
                        throw ClientAwareException::createAccessDenied();
                    }

                    $this->tokenManager->deleteToken(
                        $this->currentUser->getAccessToken(),
                        $this->currentUser->getUserId(),
                    );
                }
            )
            ->build();
    }
}
