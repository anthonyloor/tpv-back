<?php

namespace App\Repository;

use App\Entity\PsStockAvailable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsStockAvailableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsStockAvailable::class);
    }

    public function findOneByProductAttributeShop($idProduct, $idProductAttributte, $idShop):PsStockAvailable
    {
        return $this->createQueryBuilder('p')
        ->andWhere('p.id_product = :idProduct')
        ->andWhere('p.id_product_attribute = :idProductAttribute')
        ->andWhere('p.id_shop = :shop')
        ->setParameter('idProduct', $idProduct)
        ->setParameter('idProductAttribute', $idProductAttributte)
        ->setParameter('shop', $idShop)
        ->getQuery()
        ->getOneOrNullResult();
    }
}