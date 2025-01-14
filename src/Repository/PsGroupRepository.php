<?php

namespace App\Repository;

use App\Entity\PsGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsGroup::class);
    }

    public function findAllGroups(): array
    {
        return $this->createQueryBuilder('g')
            ->getQuery()
            ->getResult();
    }
}
