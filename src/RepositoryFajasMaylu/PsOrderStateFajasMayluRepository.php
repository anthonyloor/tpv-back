<?php

namespace App\RepositoryFajasMaylu;

use App\EntityFajasMaylu\PsOrderState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PsOrderStateFajasMayluRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrderState::class);
        $this->em = $registry->getManager('fajas_maylu');
    }

    public function findById(int $id)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from(PsOrderState::class, 'o')
            ->where('o.idOrderState = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}