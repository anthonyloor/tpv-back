<?php

namespace App\Repository;

use App\Entity\PsSpecificPriceRuleConditionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsSpecificPriceRuleConditionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsSpecificPriceRuleConditionGroup::class);
    }    
    
}