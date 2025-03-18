<?php

namespace App\RepositoryFajasMaylu;

use App\EntityFajasMaylu\PsOrderState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsOrderStateFajasMayluRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrderState::class);
    }
}