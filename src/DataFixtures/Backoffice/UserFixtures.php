<?php

namespace App\DataFixtures\Backoffice;

use App\Backoffice\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        protected UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setRoles(roles: ['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $faker->password);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
