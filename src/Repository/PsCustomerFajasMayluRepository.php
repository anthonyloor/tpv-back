<?php

namespace App\Repository;

use App\EntityFajasMaylu\PsCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsCustomerFajasMayluRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsCustomer::class);
    }

    public function findAllByFullNameOrId(string $search): array
    {
        $queryBuilder = $this->createQueryBuilder('c');

        // Concatenar firstname y lastname
        $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like(
                        $queryBuilder->expr()->concat('c.firstname', $queryBuilder->expr()->literal(' '), 'c.lastname'),
                        ':search'
                    ),
                    $queryBuilder->expr()->eq('c.id_customer', ':searchId')
                )
            )
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('searchId', $search);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findAllCustomers(): array
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }


}