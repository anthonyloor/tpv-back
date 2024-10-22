<?php

namespace App\Repository;

use App\Entity\LpLicense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LpLicenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LpLicense::class);
    }

    public function findOneByLicense(string $license): ?LpLicense
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.license = :license')
            ->setParameter('license', $license)
            ->getQuery()
            ->getOneOrNullResult();
    }
}