<?php

namespace App\Service;

use App\Dto\OwnerDto;
use App\Entity\Owner;
use App\Repository\OwnerRepository;

class OwnerService
{
    public function __construct(
        private readonly OwnerRepository $ownerRepo,
    ) {}

    /**
     *
     * @return OwnerDto[]
     */
    public function getAllOwners(): array
    {
        return array_map(
            fn($owner) => OwnerDto::parseToOwnerDto($owner),
            $this->ownerRepo->findAll()
        );
    }

    /**
     * Look for a owner with a specific username
     * 
     * @param string $key
     * @return ?Owner
     */
    public function searchOwner(string $username): ?Owner
    {
        return $this->ownerRepo->findByUsername($username);
    }

    public function createOwner(OwnerDto $ownerDto): void
    {
        if ($ownerDto) {
            $owner = OwnerDto::parseToOwner($ownerDto);
            $this->ownerRepo->createOwner($owner);
        } else {
            throw throw new \InvalidArgumentException('The owner you are trying to create is not valid.');
        }
    }

    public function updateOwner(int $id, OwnerDto $ownerDto): void
    {
        $owner = $this->ownerRepo->find($id);
        if ($owner instanceof Owner) {
            $owner->setUsername($ownerDto->username)
                ->setPassword($ownerDto->password);

            $this->ownerRepo->updateOwner();
        }
    }

    public function deleteOwner(int $ownerId)
    {
        $owner = $this->ownerRepo->find($ownerId);
        if ($owner instanceof Owner) {
            $this->ownerRepo->deleteOwner($owner);
        }
    }
}
