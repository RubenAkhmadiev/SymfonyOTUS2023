<?php

namespace App\Customer\Service;

use App\Adapter\Dto\UserDto;
use App\Entity\Address;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Entity\UserTelegram;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserTelegramService $userTelegramService
    ) {
    }

    public function createOrUpdateUser(
        string $email, string $firstName, string $secondName, string $phone, string $city, string $street, string $building
    ): User
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $userProfileRepository = $this->entityManager->getRepository(UserProfile::class);
        $userTelegramRepository = $this->entityManager->getRepository(UserTelegram::class);

        $userTelegram = $userTelegramRepository->findOneBy(['telegram_id' => $telegramId]);

        if (!$userTelegram) {
            $user = new User();
        } else {
            $user = $userRepository->find($userTelegram->getUserId());
        }

        $user->setEmail($email);
        $user->setPassword('nothing');
        $user->setProfile($userProfile);
        $this->entityManager->persist($user);

        $this->userTelegramService->createRelation($user->getId(), $telegramId);
        $this->entityManager->flush();
        return $user;
    }

    public function createUser(
        string $email
    ): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword('nothing');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(
        ?int $userId, string $email
    ): User
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        $user->setEmail($email);
        $user->setPassword('nothing');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function createUserProfile($user)
    {
        if (!empty($user->getProfile())) {
            $userProfile = $userProfileRepository->find($user->getProfile()->getId());
        }

        if (!$userProfile) {
            $userProfile = new UserProfile();
        }
    }

    public function createUserAddress()
    {

    }

    public function checkExistsUser(?int $telegramId): ?int
    {
        $userTelegramRepository = $this->entityManager->getRepository(UserTelegram::class);

        $userTelegram = $userTelegramRepository->findOneBy(['telegram_id' => $telegramId]);

        return !empty($userTelegram) ? $userTelegram->getUserId() : null;
    }

    public function getUser(?int $userid): ?User
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        return $userRepository->find($userid);
    }
}
