<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i = 0; $i < 20; $i++) {
            $task = new Task();
            $task->setTitle($faker->title())
                ->setDescription($faker->text())
                ->setStatus($faker->boolean())
                ->setPriority($faker->text(10));

                $manager->persist($task);
        }

        $manager->flush();
    }
}
