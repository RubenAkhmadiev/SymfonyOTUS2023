<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenUserProvider implements UserProviderInterface
{
    public function __construct(private TokenManager $tokenManager)
    {
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        $userId = $this->tokenManager->getUserId($username)
            ?? throw new UserNotFoundException();

        return new User(
            userId: $userId,
            accessToken: $username
        );
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UserNotFoundException();
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
