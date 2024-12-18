<?php

namespace App\Repository;

use App\Entity\LpPosOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LpPosOrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LpPosOrders::class);
    }

    public function getAllByLicenseAndDate(string $license, string $date): array
    {

        $qb = $this->createQueryBuilder('o')
            ->where('o.license = :license')
            ->andWhere('o.date_add = :date')
            ->setParameter('license', $license)
            ->setParameter('date', $date);
    
        return $qb->getQuery()->getResult();
    }
    
    
    
}