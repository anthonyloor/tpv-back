<?php

namespace App\Repository;

use App\Entity\PsOrderDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PsOrderDetailRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrderDetail::class);
        $this->em = $registry->getManager('default');

    }

    public function findByOrderId(int $orderId): array
    {
        return $this->em->createQueryBuilder()
            ->select('p')
            ->from(PsOrderDetail::class, 'p')
            ->andWhere('p.id_order = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getResult();
    }
}
