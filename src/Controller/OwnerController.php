<?php

namespace App\Controller;

use App\Dto\OwnerDto;
use App\Form\OwnerDtoForm;
use App\Service\OwnerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/owner')]
final class OwnerController extends AbstractController
{
    public function __construct(private readonly OwnerService $ownerService) {}

    #[Route(path: '/all_owners', name: 'owner_allowners', methods: ['GET'])]
    public function getAllOwners(): JsonResponse
    {
        try {
            return $this->json(
                data: $this->ownerService->getAllOwners(),
                status: Response::HTTP_OK
            );
        } catch (\Throwable $throwable) {
            return $this->json(
                data: ['error' => $throwable->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route(path: '/search_owner', name: 'owner_searchowner', methods: ['GET'])]
    public function searchOwners(Request $request): JsonResponse
    {
        try {
            $username = $request->query->get('username');
            return $this->json(
                data: $this->ownerService->searchOwner($username),
                status: Response::HTTP_OK
            );
        } catch (\Throwable $throwable) {
            return $this->json(
                data: ['error' => $throwable->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route(path: '/create_owner', name: 'owner_createowner', methods: ['POST'])]
    public function createOwner(Request $request): JsonResponse
    {
        try {
            $data = json_decode(
                json: $request->getContent(),
                associative: true
            );
            $ownerDto = new OwnerDto();

            $form = $this->createForm(
                type: OwnerDtoForm::class,
                data: $ownerDto
            );
            $form->submit(
                submittedData: $data,
                clearMissing: true
            );

            if ($form->isValid()) {
                $ownerDto = $form->getData();
                $this->ownerService->createOwner($ownerDto);

                return  $this->json(
                    data: ['message' => 'Owner created succesfully.'],
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

    #[Route(path: '/update_owner/{ownerId}', name: 'owner_updateowner', methods: ['PUT', 'PATCH'])]
    public function updateOwner(int $ownerId, Request $request): JsonResponse
    {
        try {
            $data = json_decode(
                json: $request->getContent(),
                associative: true
            );
            $ownerDto = new OwnerDto();

            $form = $this->createForm(
                type: OwnerDtoForm::class,
                data: $ownerDto
            );
            $form->submit(
                submittedData: $data,
                clearMissing: true
            );

            if ($form->isValid()) {
                $ownerDto = $form->getData();
                $this->ownerService->updateOwner($ownerId, $ownerDto);

                return  $this->json(
                    data: ['message' => 'Owner updated succesfully.'],
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

    #[Route(path: '/delete_owner/{ownerId}', name: 'owner_deleteowner', methods: ['DELETE'])]
    public function deleteOwner(int $ownerId): JsonResponse
    {
        try {
            $this->ownerService->deleteOwner($ownerId);

            return $this->json(
                ['message' => 'Owner deleted.'],
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
