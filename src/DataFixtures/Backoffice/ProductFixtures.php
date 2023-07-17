<?php

namespace App\DataFixtures\Backoffice;

use App\Backoffice\Entity\Category;
use App\Backoffice\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [];

        for ($i = 0; $i < 100; $i++) {
            $category = new Category();
            $category->setName(name: 'category_' . $i);
            $manager->persist($category);

            $categories[] = $category;
        }

        shuffle($categories);
        $randCategories = array_merge([null], array_slice($categories, 0, 9));

        for ($i = 0; $i < 100; $i++) {
            $product = new Product();
            $product->setTitle('title_' . $i);
            $product->setPrice(100);

            if ($randCategory = $randCategories[array_rand($randCategories)]) {
                $product->addCategory($randCategory);
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}
