<?php

namespace App\DataFixtures\Customer;

use App\Backoffice\Entity\Partner;
use App\Backoffice\Enum\PartnerTypeEnum;
use App\Customer\Service\ProductService;
use App\Customer\Service\UserService;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly UserService $userService,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(locale: 'ru_RU');

        for ($i = 0; $i < 20; $i++) {
            $order = new Order();
            $order->setSum($faker->randomDigit);
            $order->setNumber($faker->randomDigit);
            $order->setCreationDate($faker->dateTime);

            $products = $this->productService->getProducts(1, 10);

            foreach ($products as $product) {
                $order->addProduct($product);
            }

            $users = $this->userService->getUsers();

            $order->setUser($users[random_int(1, 9)]);

            $manager->persist($order);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 10; // smaller means sooner
    }
}
