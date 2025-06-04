<?php

namespace App\Controller;

use App\Dto\TaskDto;
use App\Form\TaskDtoForm;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/task')]
final class TaskController extends AbstractController
{
    public function __construct(private readonly TaskService $taskService) {}

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

    #[Route(path: '/search_tasks', name: 'task_searchtasks', methods: ['GET'])]
    public function searchTasks(Request $request): JsonResponse
    {
        try {
            $key = $request->query->get('key');
            return $this->json(
                data: $this->taskService->searchTasks($key),
                status: Response::HTTP_OK
            );
        } catch (\Throwable $throwable) {
            return $this->json(
                data: ['error' => $throwable->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route(path: '/create_task', name: 'task_createtask', methods: ['POST'])]
    public function createTask(Request $request): JsonResponse
    {
        try {
            $data = json_decode(
                json: $request->getContent(),
                associative: true
            );
            $taskDto = new TaskDto();

            $form = $this->createForm(
                type: TaskDtoForm::class,
                data: $taskDto
            );
            $form->submit(
                submittedData: $data,
                clearMissing: true
            );

            if ($form->isValid()) {
                $taskDto = $form->getData();
                $this->taskService->createTask($taskDto);

                return  $this->json(
                    data: ['message' => 'Task created succesfully.'],
                    status: Response::HTTP_CREATED
                );
            } else {
                $errors = [];
                foreach ($form->getErrors() as $e) {
                    $errors[] = [
                        'origin' => $e->getOrigin(),
                        'cause' => $e->getCause(),
                        'message' => $e->getMessage()
                    ];
                }
                return $this->json(
                    data: ['error' => $errors],
                    status: Response::HTTP_BAD_REQUEST
                );
            }
        } catch (\Throwable $throwable) {
            return $this->json(
                data: ['error' => $throwable->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route(path: '/update_task/{taskId}', name: 'task_updatetask', requirements: ['taskId' => Requirement::DIGITS], methods: ['PUT', 'PATCH'])]
    public function updateTask(int $taskId, Request $request): JsonResponse
    {
        try {
            $data = json_decode(
                json: $request->getContent(),
                associative: true
            );
            $taskDto = new TaskDto();

            $form = $this->createForm(
                type: TaskDtoForm::class,
                data: $taskDto
            );
            $form->submit(
                submittedData: $data,
                clearMissing: true
            );

            if ($form->isValid()) {
                $taskDto = $form->getData();
                $this->taskService->updateTask($taskId, $taskDto);

                return  $this->json(
                    data: ['message' => 'Task updated succesfully.'],
                    status: Response::HTTP_OK
                );
            } else {
                $errors = [];
                foreach ($form->getErrors() as $e) {
                    $errors[] = [
                        'origin' => $e->getOrigin(),
                        'cause' => $e->getCause(),
                        'message' => $e->getMessage()
                    ];
                }
                return $this->json(
                    data: ['error' => $errors],
                    status: Response::HTTP_BAD_REQUEST
                );
            }
        } catch (\Throwable $throwable) {
            return $this->json(
                data: ['error' => $throwable->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route(path: '/delete_task/{taskId}', name: 'task_deletetask', requirements: ['taskId' => Requirement::DIGITS], methods: ['DELETE'])]
    public function deleteTask(int $taskId): JsonResponse
    {
        try {
            $this->taskService->deleteTask($taskId);

            return $this->json(
                ['message' => 'Task deleted.'],
                status: Response::HTTP_OK
            );
        } catch (\Throwable $throwable) {
            return $this->json(
                ['errors' => 'The errors : ' . $throwable->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }
}
