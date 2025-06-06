<?php

namespace App\Dto;

use App\Entity\Owner;
use App\Entity\Task;
use Symfony\Component\Validator\Constraints as Assert;

class TaskDto
{
    public ?int $id = null;

    #[Assert\NotBlank(message: 'The title cannot be empty')]
    public string $title;

    public ?string $description = null;

    #[Assert\NotNull(message: 'The status should be set')]
    public bool $status;

    #[Assert\NotBlank(message: 'The priority cannot be empty')]
    public string $priority;

    #[Assert\NotNull(message: 'The owner of the task should be known')]
    public int $ownerId;

    public static function parseToTask(TaskDto $taskDto, Owner $owner): Task
    {
        $task = (new Task())
            ->setTitle($taskDto->title)
            ->setDescription($taskDto->description)
            ->setStatus($taskDto->status)
            ->setPriority($taskDto->priority)
            ->setOwner($owner);
        if ($taskDto->id != null) $task->setId($taskDto->id);

        return $task;
    }

    public static function parseToTaskDto(Task $task): TaskDto
    {
        $taskDto = new TaskDto();
        $taskDto->id = $task->getId();
        $taskDto->title = $task->getTitle();
        $taskDto->description = $task->getDescription();
        $taskDto->status = $task->getStatus();
        $taskDto->priority = $task->getPriority();
        $taskDto->ownerId = $task->getOwner()->getId();

        return $taskDto;
    }
}
