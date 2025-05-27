<?php

namespace App\Service;

use App\Dto\TaskDto;
use App\Repository\TaskRepository;

class TaskService
{
    public function __construct(private TaskRepository $taskRepo) {}

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
        if ($taskDto != null) {
            $task = TaskDto::parseToTask($taskDto);
            $this->taskRepo->createTask($task);
        } else {
            throw throw new \InvalidArgumentException('The task you are trying to create is not valid.');
        }
    }

    public function updateTask(int $id, TaskDto $taskDto): void
    {
        $task = $this->taskRepo->find($id);
        if ($task) {
            $task->setTitle($taskDto->title)
                ->setDescription($taskDto->description)
                ->setStatus($taskDto->status)
                ->setPriority($taskDto->priority);

            $this->taskRepo->updateTask();
        }
    }

    public function deleteTask(int $id)
    {
        $task = $this->taskRepo->find($id);
        if ($task) {
            $this->taskRepo->deleteTask($task);
        }
    }
}
