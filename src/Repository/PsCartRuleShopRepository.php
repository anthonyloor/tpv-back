<?php

namespace App\Repository;

use App\Entity\PsCartRuleShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsCartRuleShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsCartRuleShop::class);
    }

    public function findByIdCartRule(int $id): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id_cart_rule = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
