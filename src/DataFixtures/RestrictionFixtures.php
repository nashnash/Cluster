<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Restriction;

class RestrictionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = \Faker\Factory::create('fr_FR');

        for ($i=0; $i<4; $i++) {
            $restriction = new Restriction();
            $restriction->setName($faker->name);
            $manager->persist($restriction);
        }

        $manager->flush();
    }
}
