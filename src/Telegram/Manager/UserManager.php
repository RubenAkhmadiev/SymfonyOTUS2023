<?php

namespace App\Telegram\Manager;

use App\Entity\User;
use App\Entity\UserProfile;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createOrUpdateUser(
        int $telegramId, string $firstName, string $secondName, string $phone, string $address
    ): User
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $userProfileRepository = $this->entityManager->getRepository(UserProfile::class);

        $user = $userRepository->findOneBy(['telegram_id' => $telegramId]);

        if (!$user) {
            $user = new User();
            $user->setTelegramId($telegramId);
        }

        $userProfile = null;

        if (!empty($user->getProfile())) {
            $userProfile = $userProfileRepository->find($user->getProfile()->getId());
        }

        if (!$userProfile) {
            $userProfile = new UserProfile();
        }

        $userProfile->setFirstName($firstName);
        $userProfile->setSecondName($secondName);
        $userProfile->setPhone($phone);
        $userProfile->setAddresses(['address' => $address]);
        $userProfile->setPhone($phone);
        $userProfile->setBirthDay(new DateTime());
        $this->entityManager->persist($userProfile);
        $this->entityManager->flush();

        $user->setLogin($firstName . $secondName);
        $user->setPassword('nothing');
        $user->setCreationDate(new DateTime());
        $user->setProfile($userProfile);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
