<?php

namespace App\DataFixtures\Customer;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ItemFixtures  extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $partner = new Item();
            $partner->setName($faker->streetSuffix);
            $partner->setPrice($faker->randomDigit);
            $manager->persist($partner);
        }

        $manager->flush();
    }
}
