<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Owner;
use Faker\Factory;

class OwnerFixtures extends Fixture
{    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $owner = new Owner();
            $owner->setUsername($faker->userName())
                ->setPassword($faker->password());

            $manager->persist($owner);
        }
        $manager->flush();
    }
}
