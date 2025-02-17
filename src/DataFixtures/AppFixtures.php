<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product -> setName('First');
        $product -> setPrice(10000);
        $product -> setDescription('This is the first product');
        $product -> setUserId(1);
        $manager->persist($product);

        $product = new Product();
        $product -> setName('second');
        $product -> setPrice(20000);
        $product -> setDescription('This is the second product');
        $product -> setUserId(1);
        $manager->persist($product);

        $manager->flush();
    }
}
