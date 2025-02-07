<?php

namespace App\Repository;

use App\Entity\LpWarehouseMovement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LpWarehouseMovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LpWarehouseMovement::class); // Cambiado aquí
    }

    public function findByDateRange(\DateTime $date1, \DateTime $date2): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.date_add BETWEEN :date1 AND :date2')
            ->setParameter('date1', $date1->format('Y-m-d H:i:s'))
            ->setParameter('date2', $date2->format('Y-m-d H:i:s'))
            ->orderBy('w.date_add', 'ASC')
            ->getQuery()
            ->getResult();
    }
}