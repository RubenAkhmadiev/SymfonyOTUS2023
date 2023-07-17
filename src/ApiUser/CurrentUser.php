<?php

namespace App\ApiUser;

use App\Adapter\Dto\UserProfileDto;
use App\Entity\Address;
use App\Repository\UserProfileRepository;
use DateTime;
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
    private ?int $userId;
    private UserProfileDto $profile;

    /**
     * @throws Throwable
     */
    public function __construct(
        RequestStack $requestStack,
        Security $security,
        private readonly UserProfileRepository $userProfileRepository,
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
                $this->userId = $user->getUserId();
                $this->initProfile($user->getUserId());
            } catch (UserNotFoundException) {
                $this->initUnauthorized();
            }
        }
    }

    private function initUnauthorized(): void
    {
        $this->isAuthorized = false;
        $this->accessToken = null;
        $this->userId = null;
        $this->profile = UserProfileDto::createEmpty();
    }

    private function initProfile(int $userId): void
    {
        $profile = $this->userProfileRepository->findOneByUserId($userId);

        if ($profile === null) {
            $this->profile = UserProfileDto::createEmpty();
        } else {
            $this->profile = UserProfileDto::fromEntity($profile);
        }
    }

    public function isAuthorized(): bool
    {
        return $this->isAuthorized;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getProfile(): UserProfileDto
    {
        return $this->profile;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }
}
