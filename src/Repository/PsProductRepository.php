<?php

namespace App\Repository;


use App\Entity\PsProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dom\Entity;


class PsProductRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsProduct::class);
        $this->em = $registry->getManager('default');
    }   
    
    public function findOneById(int $id): ?PsProduct
    {
        return $this->em->createQueryBuilder()
            ->select('p')
            ->from(PsProduct::class, 'p')
            ->andWhere('p.id_product = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}