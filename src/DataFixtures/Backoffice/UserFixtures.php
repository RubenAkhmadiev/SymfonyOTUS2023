<?php

namespace App\DataFixtures\Backoffice;

use App\Backoffice\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail(sprintf('email_%s@site.com', $i));
            $user->setPassword('password');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
