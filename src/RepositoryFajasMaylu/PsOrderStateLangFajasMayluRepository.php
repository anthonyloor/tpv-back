<?php

namespace App\RepositoryFajasMaylu;

use App\EntityFajasMaylu\PsOrderStateLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsOrderStateLangFajasMayluRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrderStateLang::class);
    }
}