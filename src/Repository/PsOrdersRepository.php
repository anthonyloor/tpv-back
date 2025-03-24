<?php

namespace App\Repository;

use App\Entity\PsOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PsOrdersRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrders::class);
        $this->em = $registry->getManager('default');
    }

    public function findOrdersByShop($idShop)
    {
        return $this->em->createQueryBuilder()
            ->select('o')
            ->from(PsOrders::class, 'o')
            ->where('o.id_shop = :id_shop')
            ->setParameter('id_shop', $idShop)
            ->orderBy('o.date_add', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    public function findById($idOrder): ?PsOrders
    {
        return $this->em->createQueryBuilder()
            ->select('o')
            ->from(PsOrders::class, 'o')
            ->where('o.id_order = :id_order')
            ->setParameter('id_order', $idOrder)
            ->getQuery()
            ->getOneOrNullResult();
    }
}