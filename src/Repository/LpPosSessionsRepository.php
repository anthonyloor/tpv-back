<?php

namespace App\Repository;

use App\Entity\LpPosSessions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LpPosSessionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LpPosSessions::class); // Cambiado aquí
    }

    public function findOneActiveByLicense(string $license): ?LpPosSessions
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.license = :license') // Esto asume que tienes un campo "license" en LpPosSessions
            ->andWhere('p.active = 1')
            ->setParameter('license', $license)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
