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
}
