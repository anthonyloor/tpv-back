<?php

namespace App\RepositoryFajasMaylu;
use App\EntityFajasMaylu\PsCartRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

class PsCartRuleFajasMayluRepository extends ServiceEntityRepository
{
    private EntityManager $em;
    public function __construct(ManagerRegistry $registry, EntityManager $em)
    {
        $this->em = $em;
        parent::__construct($registry, PsCartRule::class);
    }

    public function findByIdCartRule(int $id)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from(PsCartRule::class, 'o')
            ->where('o.id_cart_rule = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

}