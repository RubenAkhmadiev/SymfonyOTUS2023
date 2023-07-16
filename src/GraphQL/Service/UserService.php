<?php

namespace App\GraphQL\Service;

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
        ?int $telegramId, string $email, string $firstName, string $secondName, string $phone, string $city, string $street, string $building
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

        $userProfile = null;

        if (!empty($user->getProfile())) {
            $userProfile = $userProfileRepository->find($user->getProfile()->getId());
        }

        if (!$userProfile) {
            $userProfile = new UserProfile();
        }

        $address = new Address();
        $address->setCity($city);
        $address->setStreet($street);
        $address->setBuilding($building);
        $this->entityManager->persist($address);

        $userProfile->setFirstName($firstName);
        $userProfile->setSecondName($secondName);
        $userProfile->setPhone($phone);
        $userProfile->addAddress($address);
        $userProfile->setPhone($phone);
        $userProfile->setBirthDay(new DateTime());
        $this->entityManager->persist($userProfile);

        $user->setEmail($email);
        $user->setPassword('nothing');
        $user->setProfile($userProfile);
        $this->entityManager->persist($user);

        $this->userTelegramService->createRelation($user->getId(), $telegramId);
        $this->entityManager->flush();
        return $user;
    }
}
