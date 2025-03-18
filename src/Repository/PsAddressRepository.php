<?php

namespace App\Repository;
use App\Entity\PsAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PsAddressRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsAddress::class);
        $this->em = $registry->getManager('default');
    }

    public function findAllByCustomerId(int $idCustomer): array
    {
        return $this->em->createQueryBuilder()
            ->select('a')
            ->from(PsAddress::class, 'a')
            ->andWhere('a.id_customer = :idCustomer')
            ->setParameter('idCustomer', $idCustomer)
            ->getQuery()
            ->getResult();
    }
}