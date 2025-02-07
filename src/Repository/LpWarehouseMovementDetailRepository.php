<?php

namespace App\Repository;

use App\Entity\LpWarehouseMovementDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LpWarehouseMovementDetailRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LpWarehouseMovementDetails::class);
    }

}