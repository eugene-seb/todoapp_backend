<?php

namespace App\Dto;

use App\Entity\Owner;
use Symfony\Component\Validator\Constraints as Assert;

class OwnerDto
{
    public ?int $id = null;

    #[Assert\NotBlank(message: 'The username cannot be empty')]
    public ?string $username = null;

    #[Assert\NotBlank(message: 'The password cannot be empty')]
    public ?string $password = null;

    public static function parseToOwner(OwnerDto $ownerDto): Owner
    {
        $owner = (new Owner())
            ->setUsername($ownerDto->username)
            ->setPassword($ownerDto->password);
        if ($ownerDto->id != null) $owner->setId($ownerDto->id);

        return $owner;
    }

    public static function parseToOwnerDto(Owner $owner): OwnerDto
    {
        $ownerDto = new OwnerDto();
        $ownerDto->id = $owner->getId();
        $ownerDto->username = $owner->getUsername();
        $ownerDto->password = $owner->getPassword();

        return $ownerDto;
    }
}
