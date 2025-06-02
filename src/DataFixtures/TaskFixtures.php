<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Task;
use App\Repository\OwnerRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly OwnerRepository $ownerRepo) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $ownerList = $this->ownerRepo->findAll();

        for ($i = 0; $i < 20; $i++) {
            $task = new Task();
            $task->setTitle($faker->title())
                ->setDescription($faker->text())
                ->setStatus($faker->boolean())
                ->setPriority($faker->text(10))
                ->setOwner($faker->randomElement($ownerList));

            $manager->persist($task);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [OwnerFixtures::class];
    }
}
