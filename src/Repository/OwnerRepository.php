<?php

namespace App\Repository;

use App\Entity\Owner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * @extends ServiceEntityRepository<Owner>
 */
class OwnerRepository extends ServiceEntityRepository
{
    public function __construct(private readonly EntityManagerInterface $em, ManagerRegistry $registry)
    {
        parent::__construct($registry, Owner::class);
    }

    /**
     * Returns a Owner from the database, or null if none exist.
     *
     * @return Owner|null
     */
    public function findOneOwner(): ?Owner
    {
        return $this->createQueryBuilder('o')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     *
     * @param Owner $owner
     * @return void
     */
    public function createOwner(Owner $owner): void
    {
        if (!$owner) {
            throw new InvalidArgumentException("Owner not found.");
        }
        $this->em->persist($owner);
        $this->em->flush();
    }

    public function updateOwner(): void
    {
        $this->em->flush();
    }

    public function deleteOwner(Owner $owner): void
    {
        if (!$owner) {
            throw new InvalidArgumentException("Owner not found.");
        }
        $this->em->remove($owner);
        $this->em->flush();
    }
}
