<?php

namespace App\Repository;

use App\Entity\LpWarehouseMovementIncidents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LpWarehouseMovementIncidentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LpWarehouseMovementIncidents::class);
    }
}