<?php

namespace App\Repository;

use App\Entity\LpControlStockHistory;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LpControlStockHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LpControlStockHistory::class);
    }

    public function findByTransactionId($transactionId): array
    {
        return $this->createQueryBuilder('l')
            ->select('l.id_control_stock')
            ->where('l.id_transaction_detail = :transactionId')
            ->setParameter('transactionId', $transactionId)
            ->getQuery()
            ->getResult();
    }
}