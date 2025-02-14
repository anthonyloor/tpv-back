<?php

namespace App\RepositoryFajasMaylu;

use App\EntityFajasMaylu\PsCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsCustomerFajasMayluRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsCustomer::class);
    }

    public function findAllByFullNameOrPhone(string $search): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.addresses', 'a') // Usa el nombre correcto de la relación
            ->where('CONCAT(c.firstname, \' \', c.lastname) LIKE :search')
            ->orWhere('a.phone LIKE :search')
            ->orWhere('a.phone_mobile LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }
    
    public function findAllCustomers(): array
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }


}