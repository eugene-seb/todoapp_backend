<?php

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    public function __construct(private TaskService $taskService) {}

    #[Route(path: '/all_tasks', name: 'task_alltasks', methods: ['GET'])]
    public function getAllTasks(): JsonResponse
    {
        try {
            return $this->json(
                data: $this->taskService->getAllTasks(),
                status: Response::HTTP_OK
            );
        } catch (\Throwable $throwable) {
            return $this->json(
                data: ['error' => $throwable->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }

    // #[Route(path:'/update_task/{id}', name: 'task_updatetask', methods:['PUT', 'PATCH'])]
}
