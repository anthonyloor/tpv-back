<?php

namespace App\Repository;

use App\Entity\PsOrderDetail;
use App\Entity\PsProduct;
use App\Entity\PsProductAttribute;
use App\Entity\PsProductAttributeCombination;
use App\Entity\PsAttributeLang;
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
            ->andWhere('p.idOrder = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getResult();
    }

    public function getSalesReturnsByReference(string $reference): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('
            p.reference AS referencia,
            GROUP_CONCAT(DISTINCT al.name ORDER BY al.idAttribute SEPARATOR " - ") AS combinacion,
            SUM(CASE WHEN od.product_quantity > 0 THEN od.product_quantity ELSE 0 END) AS num_ventas,
            SUM(CASE WHEN od.product_quantity < 0 THEN -od.product_quantity ELSE 0 END) AS num_devoluciones,
            SUM(od.product_quantity) AS total
        ')
            ->from(PsOrderDetail::class, 'od')
            ->leftJoin(PsProduct::class, 'p', 'WITH', 'p.id_product = od.product_id')
            ->leftJoin(PsProductAttribute::class, 'pa', 'WITH', 'pa.id_product_attribute = od.product_attribute_id')
            ->leftJoin(PsProductAttributeCombination::class, 'pac', 'WITH', 'pac.id_product_attribute = pa.id_product_attribute')
            ->leftJoin(PsAttributeLang::class, 'al', 'WITH', 'pac.idAttribute = al.idAttribute AND al.id_lang = 1')
            ->where('p.reference = :ref OR pa.ean13 = :ref OR p.ean13 = :ref')
            ->groupBy('p.reference, pa.id_product_attribute')
            ->setParameter('ref', $reference);

        return $qb->getQuery()->getResult();
    }
}
