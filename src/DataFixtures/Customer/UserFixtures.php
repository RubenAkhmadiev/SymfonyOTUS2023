<?php

namespace App\DataFixtures\Customer;

use App\Customer\Service\UserService;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(locale: 'ru_RU');

        for ($i = 0; $i < 20; $i++) {
            $this->userService->createUser($faker->email, $faker->password);
        }
    }

    public function getOrder(): int
    {
        return 9; // smaller means sooner
    }
}
