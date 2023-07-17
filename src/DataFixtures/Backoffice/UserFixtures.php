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
        $faker = Factory::create(locale: 'ru_RU');

        // admin
        $adminUser = $this->makeUser(email: 'admin@site.com', roles: ['ROLE_ADMIN']);
        $manager->persist($adminUser);

        // partner
        $partnerUser = $this->makeUser('partner_1@site.com');
        $manager->persist($partnerUser);

        // other users
        for ($i = 0; $i < 5; $i++) {
            $otherUser = $this->makeUser(email: $faker->email);
            $manager->persist($otherUser);
        }

        $manager->flush();
    }

    private function makeUser(string $email, array $roles = [], string $password = 'password'): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(roles: array_merge(['ROLE_USER'], $roles));
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        return $user;
    }
}
