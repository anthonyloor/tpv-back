<?php

namespace App\RepositoryFajasMaylu;
use App\EntityFajasMaylu\PsOrderCartRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class PsOrderCartRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrderCartRule::class);
    }

    public function findByIdOrder(int $id)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from(PsOrderCartRule::class, 'o')
            ->where('o.id_order = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

}