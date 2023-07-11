<?php

namespace App\Security;

use App\Http\Response\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

class TokenAuthenticator implements AuthenticationEntryPointInterface, AuthenticatorInterface
{
    public function __construct(private TokenManager $tokenManager)
    {
    }

    public function supports(Request $request): bool
    {
        // формат хедера: "Bearer <token>"
        return (bool) preg_match(
            '/^(Bearer )[a-f0-9]{32}$/',
            $request->headers->get('authorization')
        );
    }

    public function getCredentials(Request $request): array
    {
        return ['token' => substr((string)$request->headers->get('authorization'), 7)];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $token = $credentials['token'];

        if (null === $token) {
            return null;
        }

        return $userProvider->loadUserByUsername($token);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Для GraphQL контроллера мы оставляем возможность реагировать на
        // неавторизованных пользователей по своему, путем показа ошибок
        // авторизации для конкретных полей графа
        if (str_starts_with($request->getPathInfo(), '/graphql')) {
            return null;
        }

        return new JsonResponse(
            ['details' => null, 'message' => 'Access denied'],
            Response::HTTP_FORBIDDEN
        );
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse(
            ['details' => null, 'message' => 'Authentication Required'],
            JsonResponse::HTTP_UNAUTHORIZED
        );
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    public function authenticate(Request $request)
    {
        $token = $this->tokenManager->getExistsOrCreateToken($request->getUser());

        return new SelfValidatingPassport(new UserBadge($token));
    }

    public function createAuthenticatedToken(UserInterface $user, string $providerKey)
    {
        return new PostAuthenticationGuardToken(
            $user,
            $providerKey,
            $user->getRoles()
        );
    }
}
