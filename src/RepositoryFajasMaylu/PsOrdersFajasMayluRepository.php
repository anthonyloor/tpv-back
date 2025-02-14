<?php

namespace App\RepositoryFajasMaylu;

use App\EntityFajasMaylu\PsOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsOrdersFajasMayluRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrders::class);
    }

    public function findOrdersByShop($idShop)
    {
        return $this->createQueryBuilder('o')
            ->where('o.id_shop = :id_shop')
            ->setParameter('id_shop', $idShop)
            ->orderBy('o.date_add', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }
}