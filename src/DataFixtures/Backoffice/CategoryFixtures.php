<?php

namespace App\DataFixtures\Backoffice;

use App\Backoffice\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $partner = new Category();
            $partner->setName($faker->streetSuffix);
            $manager->persist($partner);
        }

        $manager->flush();
    }
}
