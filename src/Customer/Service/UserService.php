<?php

namespace App\Customer\Service;

use App\Entity\Address;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Entity\UserTelegram;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
        string $email,
        string $telegramId
    ): User {
        $userRepository = $this->entityManager->getRepository(User::class);
        $userTelegramRepository = $this->entityManager->getRepository(UserTelegram::class);

        $userTelegram = $userTelegramRepository->findOneBy(['telegram_id' => $telegramId]);

        if (!$userTelegram) {
            $user = new User();
        } else {
            $user = $userRepository->find($userTelegram->getUserId());
        }

        $user->setEmail($email);
        $user->setPassword('nothing');
        $user->setProfile(null);
        $this->entityManager->persist($user);

        $this->userTelegramService->createRelation($user->getId(), $telegramId);
        $this->entityManager->flush();
        return $user;
    }

    public function createUser(
        string $email,
        string $password
    ): User {
        $user = new User();
        $user->setEmail($email);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );

        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(
        ?int $userId,
        string $email,
        string $password
    ): User {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        $user->setEmail($email);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );

        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @throws Exception
     */
    public function createUserProfile(User $user, string $firstName, string $secondName, string $phone): void
    {
        if (!$user->getProfile()) {
            $userProfile = new UserProfile();
            $userProfile->setUser($user);
            $userProfile->setFirstName($firstName);
            $userProfile->setSecondName($secondName);
            $userProfile->setPhone($secondName);
            $this->entityManager->persist($userProfile);
            $this->entityManager->flush();
        } else {
            throw new Exception('Профиль для данного пользователя уже существует');
        }
    }

    public function createUserAddress(UserProfile $userProfile, string $city, string $street, string $building): void
    {
        $address = new Address();
        $address->setCity($city);
        $address->setStreet($street);
        $address->setBuilding($building);
        $address->setProfile($userProfile);
        $this->entityManager->persist($address);
        $this->entityManager->flush();
    }

    public function updateUserAddress(Address $address, string $city, string $street, string $building): void
    {
        $address->setCity($city);
        $address->setStreet($street);
        $address->setBuilding($building);
        $this->entityManager->persist($address);
        $this->entityManager->flush();
    }

    public function checkExistsUser(?int $telegramId): ?int
    {
        $userTelegramRepository = $this->entityManager->getRepository(UserTelegram::class);

        $userTelegram = $userTelegramRepository->findOneBy(['telegram_id' => $telegramId]);

        return !empty($userTelegram) ? $userTelegram->getUserId() : null;
    }

    public function getUser(?int $userid): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($userid);
    }
}
