<?php

namespace App\RepositoryFajasMaylu;

use App\EntityFajasMaylu\PsOrderDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PsOrderDetailFajasMayluRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrderDetail::class);
        $this->em = $registry->getManager('fajas_maylu');

    }

    public function findByOrderId(int $orderId): array
    {
        return $this->em->createQueryBuilder()
            ->select('p')
            ->from(PsOrderDetail::class, 'p')
            ->andWhere('p.idOrder = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getResult();
    }
}
