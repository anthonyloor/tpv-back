<?php

namespace App\Repository;
use App\Entity\PsCartRule;
use App\Entity\PsOrderCartRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class PsOrderCartRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrderCartRule::class);
    }

}