<?php

namespace App\Repository;

use App\Entity\PsOrders;
use App\Utils\Constants\Entity\PsOrderFields;
use App\Utils\Constants\DatabaseManagers;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PsOrdersRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsOrders::class);
        $this->em = $registry->getManager(DatabaseManagers::MAYRET_MANAGER);
    }

    public function findOrdersByShop($idShop)
    {
        return $this->em->createQueryBuilder()
            ->select('o')
            ->from(PsOrders::class, 'o')
            ->where('o.'.PsOrderFields::ID_SHOP.' = :id_shop')
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
            ->where('o.'.PsOrderFields::ID_ORDER.' = :id_order')
            ->setParameter('id_order', $idOrder)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLastOrdersByCustomer(int $idCustomer, int $limit = 10): array
    {
        return $this->em->createQueryBuilder()
            ->select('o')
            ->from(PsOrders::class, 'o')
            ->where('IDENTITY(o.customer) = :id_customer')
            ->setParameter('id_customer', $idCustomer)
            ->orderBy('o.date_add', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}