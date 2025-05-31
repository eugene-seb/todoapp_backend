<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use App\Entity\Task;
use App\Repository\OwnerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(private readonly OwnerRepository $ownerRepo) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $owner = new Owner();
            $owner->setUsername($faker->userName())
                ->setPassword($faker->password());

            $manager->persist($owner);
        }

        $manager->flush(); // I need to do that in order for Doctrine to generate the ID of Doctrine

        for ($i = 0; $i < 20; $i++) {
            $owner = $this->ownerRepo->findOneOwner();

            $task = new Task();
            $task->setTitle($faker->title())
                ->setDescription($faker->text())
                ->setStatus($faker->boolean())
                ->setPriority($faker->text(10))
                ->setOwner($owner);

            $manager->persist($task);
        }

        $manager->flush();
    }
}
