<?php

namespace App\Customer\Service;

use App\Adapter\Dto\AddressDto;
use App\Adapter\Dto\UserProfileDto;
use App\Entity\Address;
use App\Entity\User;
use App\Entity\UserProfile;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class UserProfileService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createUserProfile(User $user, ?string $firstName, ?string $secondName, ?string $phone): ?UserProfile
    {
        $userProfile = new UserProfile();

        $userProfile->setFirstName($firstName);
        $userProfile->setSecondName($secondName);
        $userProfile->setPhone($phone);
        $userProfile->setBirthDay(new DateTime());
        $userProfile->setUser($user);
        $this->entityManager->persist($userProfile);
        $this->entityManager->flush();

        return $userProfile;
    }

    public function updateUserProfile(int $userId, ?string $firstName, ?string $secondName, ?string $phone): ?UserProfile
    {
        $userProfileRepository = $this->entityManager->getRepository(UserProfile::class);
        $userProfile = $userProfileRepository->findOneBy(['user_id' => $userId]);

        $userProfile->setFirstName($firstName);
        $userProfile->setSecondName($secondName);
        $userProfile->setPhone($phone);
        $userProfile->setBirthDay(new DateTime());
        $this->entityManager->persist($userProfile);
        $this->entityManager->flush();

        return $userProfile;
    }
}
