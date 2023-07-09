<?php

namespace App\ApiUser;

use App\GraphQL\Type\Dto\CurrentUserProfileDto;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Throwable;
use Webmozart\Assert\Assert;

final class CurrentUser
{
    private ?string $userAgent;
    private ?string $accessToken;
    private bool $isAuthorized;

    private CurrentUserProfileDto $profile;

    /**
     * @throws Throwable
     */
    public function __construct(
        RequestStack $requestStack,
        Security $security,
    ) {
        $request = $requestStack->getCurrentRequest();
        Assert::notNull($request);

        $this->userAgent = $request->headers->get('user-agent');

        $user = $security->getUser();

        if (null === $user) {
            $this->initUnauthorized();
        } else {
            try {
                $this->isAuthorized = true;
                $this->accessToken = $user->getAccessToken();
            } catch (UserNotFoundException) {
                $this->initUnauthorized();
            }
        }
    }

    private function initUnauthorized(): void
    {
        $this->isAuthorized = false;
        $this->accessToken = null;
        $this->profile = CurrentUserProfileDto::createEmpty();
    }

    public function isAuthorized(): bool
    {
        return $this->isAuthorized;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getProfile(): CurrentUserProfileDto
    {
        return $this->profile;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }
}
