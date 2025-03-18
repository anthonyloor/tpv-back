<?php

namespace App\RepositoryFajasMaylu;

use App\EntityFajasMaylu\PsCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PsCustomerFajasMayluRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsCustomer::class);
        $this->em = $registry->getManager('fajas_maylu');

    }
    public function findAllByFullNameOrPhone(string $search): array
    {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(PsCustomer::class, 'c')
            ->leftJoin('c.addresses', 'a') // Usa el nombre correcto de la relación
            ->where('CONCAT(c.firstname, \' \', c.lastname) LIKE :search')
            ->orWhere('a.phone LIKE :search')
            ->orWhere('a.phone_mobile LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }

    public function findByCustomerById(string $search): array
    {
        $queryBuilder = $this->createQueryBuilder('c');

        // Concatenar firstname y lastname
        $queryBuilder
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('c.id_customer', ':searchId')
                )
            )
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