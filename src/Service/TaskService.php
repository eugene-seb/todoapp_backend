<?php

namespace App\Service;

use App\Dto\TaskDto;
use App\Entity\Owner;
use App\Entity\Task;
use App\Repository\OwnerRepository;
use App\Repository\TaskRepository;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepo,
        private readonly OwnerRepository $ownerRepo,
    ) {}

    /**
     *
     * @return TaskDto[]
     */
    public function getAllTasks(): array
    {
        return array_map(
            fn($task) => TaskDto::parseToTaskDto($task),
            $this->taskRepo->findAll()
        );
    }

    /**
     * Search some tasks containing an expression.
     * 
     * @param string $key
     * @return TaskDto[]
     */
    public function searchTasks(string $key): array
    {
        return array_map(
            fn($task) => TaskDto::parseToTaskDto($task),
            $this->taskRepo->findByKey($key)
        );
    }

    public function createTask(TaskDto $taskDto): void
    {
        if ($taskDto) {
            $owner = $this->ownerRepo->find($taskDto->ownerId);
            if ($owner instanceof Owner) {
                $task = TaskDto::parseToTask($taskDto, $owner);
                $this->taskRepo->createTask($task);
            } else {
                throw throw new \InvalidArgumentException('The task you are trying to create has no owner.');
            }
        } else {
            throw throw new \InvalidArgumentException('The task you are trying to create is not valid.');
        }
    }

    public function updateTask(int $id, TaskDto $taskDto): void
    {
        $task = $this->taskRepo->find($id);
        if ($task instanceof Task) {
            $task->setTitle($taskDto->title)
                ->setDescription($taskDto->description)
                ->setStatus($taskDto->status)
                ->setPriority($taskDto->priority);

            $this->taskRepo->updateTask();
        }
    }

    public function deleteTask(int $taskId)
    {
        $task = $this->taskRepo->find($taskId);
        if ($task instanceof Task) {
            $owner = $this->ownerRepo->find($task->getOwner()->getId());
            if ($owner instanceof Owner) {
                $owner->removeTask($task);
                $this->taskRepo->deleteTask($task);
            }
        }
    }
}
