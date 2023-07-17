<?php

namespace App\DataFixtures\Backoffice;

use App\Backoffice\Entity\Category;
use App\Backoffice\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(locale: 'ru_RU');

        // category 1
        $category1 = $this->makeCategory(name: self::category_1());
        $manager->persist($category1);

        foreach (self::products_1() as $productName) {
            $product = $this->makeProduct(
                title: $productName[1],
                price: $faker->randomFloat(min: 1, max: 1000),
                category: $category1,
            );
            $manager->persist($product);
        }

        // category 2
        $category2 = $this->makeCategory(name: self::category_2());
        $manager->persist($category2);

        foreach (self::products_2() as $productName) {
            $product = $this->makeProduct(
                title: $productName[1],
                price: $faker->randomFloat(min: 1, max: 1000),
                category: $category2,
            );
            $manager->persist($product);
        }

        // category 3
        $category3 = $this->makeCategory(name: self::category_3());
        $manager->persist($category3);

        foreach (self::products_3() as $productName) {
            $product = $this->makeProduct(
                title: $productName[1],
                price: $faker->randomFloat(min: 1, max: 1000),
                category: $category3,
            );
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function makeCategory(string $name): Category
    {
        $category = new Category();
        $category->setName($name);

        return $category;
    }

    private function makeProduct(string $title, float $price, Category $category): Product
    {
        $product = new Product();
        $product->setTitle($title);
        $product->setPrice($price);
        $product->addCategory($category);

        return $product;
    }

    private static function category_1(): string
    {
        return 'Закуски, салаты и супы';
    }

    private static function products_1(): array
    {
        return [
            ['Onion soup', 'Луковый суп'],
            ['Tomato soup', 'Томатный суп'],
            ['Mushroom cream soup', 'Грибной суп-пюре'],
            ['Chicken broth', 'Куриный бульон'],
            ['Fish soup', 'Рыбный суп'],
            ['Miso soup', 'Мисо-суп'],
            ['Vegetable soup', 'Овощной суп'],
            ['Goulash soup', 'Суп-гуляш'],
            ['Caesar salad (with shrimp, chicken, salmon)', 'Салат Цезарь (с креветками, курицей, семгой)'],
            ['Greek salad', 'Греческий салат'],
            ['Prawn cocktail', 'Салат-коктейль из креветок'],
            ['Garden fresh salad', 'Салат из свежих овощей'],
            ['Nicoise salad', 'Салат Нисуаз'],
            ['Caprese salad', 'Салат Капрезе'],
            ['Chips (French fries)', 'Картофель фри'],
            ['Nachos', 'Кукурузные чипсы с сыром'],
            ['Onion rings', 'Луковые кольца'],
            ['Garlic bread', 'Чесночные гренки'],
            ['Cheese sticks', 'Сырные палочки'],
            ['Potato pancakes', 'Драники (картофельные оладьи)'],
            ['Club sandwich', 'Клаб-сэндвич'],
            ['Platter (cheese, fruit, fish, meat)', 'Тарелка-ассорти (сырная, фруктовая, рыбная, мясная)'],
            ['Carpaccio from beef tenderloin', 'Карпаччо из сырой говяжьей вырезки'],
            ['Sauce', 'Соус'],
        ];
    }

    private static function category_2(): string
    {
        return 'Мясо и основные блюда';
    }

    public static function products_2(): array
    {
        return [
            ['BBQ ribs', 'Ребрышки барбекю'],
            ['Cheddar and bacon burger', 'Бургер с сыром Чеддер и беконом'],
            ['Cheeseburger', 'Чизбургер'],
            ['Tuna and egg sandwich', 'Сэндвич с тунцом и яйцом'],
            ['Fish and chips', 'Рыба с картофелем фри'],
            ['Steak', 'Стейк'],
            ['Roast chicken and potatoes', 'Запеченная курица с картофелем'],
            ['Spaghetti Bolognese', 'Спагетти с соусом болоньезе'],
            ['Lasagna', 'Лазанья'],
            ['Pasta Carbonara', 'Паста Карбонара'],
            ['Risotto', 'Ризотто'],
            ['Pizza', 'Пицца'],
            ['Oysters', 'Устрицы'],
            ['Roast', 'Жаркое'],
            ['Stew', 'Рагу'],
            ['Pork chop', 'Свиная отбивная'],
            ['Mac’n’cheese', 'Макароны с сыром'],
            ['Seafood pasta', 'Паста с морепродуктами'],
            ['Chicken noodles', 'Лапша с курицей'],
            ['Wok noodles', 'Вок-лапша'],
            ['Fried rice', 'Жареный рис'],
            ['Shish kebab', 'Шашлык'],
            ['Gyro', 'Кебаб, шаурма'],
            ['Meatballs', 'Фрикадельки'],
            ['Schnitzel', 'Шницель'],
        ];
    }

    private static function category_3(): string
    {
        return 'Гарниры, завтраки и десерты';
    }

    private static function products_3(): array
    {
        return [
            ['Grilled vegetables', 'Овощи-гриль'],
            ['Chips / French fries', 'Картофель-фри'],
            ['Mashed potatoes', 'Картофельное пюре'],
            ['Boiled potatoes', 'Отварной картофель'],
            ['Rice', 'Рис'],
            ['Scrambled eggs / Omelette', 'Омлет'],
            ['Fried eggs', 'Яичница'],
            ['Bacon and eggs', 'Яичница с беконом'],
            ['Porridge', 'Каша'],
            ['Pancakes', 'Блины / оладьи'],
            ['Cheesecake', 'Чизкейк'],
            ['Tiramisu', 'Тирамису'],
            ['Homemade apple tart', 'Домашний яблочный пирог'],
            ['Cherry pie', 'Вишневый пирог'],
            ['Chocolate brownie', 'Шоколадный брауни'],
            ['Ice-cream', 'Мороженое'],
            ['Vanilla pudding', 'Ванильный пудинг'],
        ];
    }
}
