<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Task::class);
    }

    /**
     *
     * @param string $key
     * @return Task[]
     */
    public function findByKey(string $key): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.title LIKE :key OR t.description LIKE :key ')
            ->setParameter('key', '%' . $key . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     *
     * @param Task $task
     * @return void
     */
    public function createTask(Task $task): void
    {
        if (!$task) {
            throw new InvalidArgumentException("Task not found.");
        }
        $this->em->persist($task);
        $this->em->flush();
    }

    public function updateTask(): void
    {
        $this->em->flush();
    }

    public function deleteTask(Task $task): void
    {
        if (!$task) {
            throw new InvalidArgumentException("Task not found.");
        }
        $this->em->remove($task);
        $this->em->flush();
    }
}
