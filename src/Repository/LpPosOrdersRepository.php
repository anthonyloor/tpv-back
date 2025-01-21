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

    public function getAllByLicenseAndDate(string $license, string $date1, string $date2): array
    {

        $qb = $this->createQueryBuilder('o')
            ->where('o.license = :license')
            ->andWhere('o.date_add BETWEEN :date1 AND :date2')
            ->setParameter('license', $license)
            ->setParameter('date1', $date1)
            ->setParameter('date2', $date2);

    
        return $qb->getQuery()->getResult();
    }
    
    
    
}