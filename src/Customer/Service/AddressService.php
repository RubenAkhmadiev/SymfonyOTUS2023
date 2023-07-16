<?php

namespace App\Customer\Service;

use App\Entity\Address;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;

class AddressService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        protected UserProfileService $userProfileService,
    ) {
    }

    public function createAddress(?int $profileId, ?string $city, ?string $street, ?string $building): ?Address
    {
        $userProfileRepository = $this->entityManager->getRepository(UserProfile::class);
        $userProfile = $userProfileRepository->find($profileId);

        $address = new Address();
        $address->setCity($city);
        $address->setStreet($street);
        $address->setBuilding($building);
        $address->setProfile($userProfile);
        $this->entityManager->persist($address);
        $this->entityManager->flush();;

        return $address;
    }

    public function updateAddress(?int $profileId, ?string $city, ?string $street, ?string $building): ?Address
    {
        $addressRepository = $this->entityManager->getRepository(Address::class);

        $address = $addressRepository->findOneBy(['profile_id' => $profileId]);
        $address->setCity($city);
        $address->setStreet($street);
        $address->setBuilding($building);
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return $address;
    }
}
