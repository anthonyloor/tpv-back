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

    public function findOneByLicenseAndIdShop(string $license, int $id_shop): ?LpLicense
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.license = :license')
            ->andWhere('l.id_shop = :shop')
            ->setParameter('license', $license)
            ->setParameter('shop', $id_shop)
            ->getQuery()
            ->getOneOrNullResult();
    }
}