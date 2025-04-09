<?php

namespace App\RepositoryFajasMaylu;
use App\EntityFajasMaylu\PsOrderCartRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\Constants\DatabaseManagers;


class PsOrderCartRuleRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrderCartRule::class);
        $this->em = $registry->getManager(DatabaseManagers::FAJASMAYLU_MANAGER);
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