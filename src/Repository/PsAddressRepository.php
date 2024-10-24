<?php

namespace App\Repository;
use App\Entity\PsAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsAddress::class);
    }

    public function findAllByCustomerId(int $idCustomer): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id_customer = :idCustomer')
            ->setParameter('idCustomer', $idCustomer)
            ->getQuery()
            ->getResult();
    }
}