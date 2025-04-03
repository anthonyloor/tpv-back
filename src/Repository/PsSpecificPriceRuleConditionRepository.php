<?php

namespace App\Repository;

use App\Entity\PsSpecificPriceRuleCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsSpecificPriceRuleConditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsSpecificPriceRuleCondition::class);
    }    
    
}