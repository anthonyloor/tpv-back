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
    $conn = $this->em->getConnection();

    $sql = '
        SELECT 
            p.reference AS referencia,
            (
                SELECT GROUP_CONCAT(DISTINCT al.name ORDER BY al.id_attribute SEPARATOR " - ")
                FROM ps_product_attribute_combination pac
                INNER JOIN ps_attribute_lang al ON pac.id_attribute = al.id_attribute AND al.id_lang = 1
                WHERE pac.id_product_attribute = pa.id_product_attribute
            ) AS combinacion,
            SUM(CASE WHEN od.product_quantity > 0 THEN od.product_quantity ELSE 0 END) AS num_ventas,
            SUM(CASE WHEN od.product_quantity < 0 THEN -od.product_quantity ELSE 0 END) AS num_devoluciones,
            SUM(od.product_quantity) AS total
        FROM ps_order_detail od
        LEFT JOIN ps_product p ON p.id_product = od.product_id
        LEFT JOIN ps_product_attribute pa ON pa.id_product_attribute = od.product_attribute_id
        WHERE p.reference = :ref OR pa.ean13 = :ref OR p.ean13 = :ref
        GROUP BY p.reference, pa.id_product_attribute
    ';

    $stmt = $conn->prepare($sql);
    $stmt->bindValue('ref', $reference);
    return $stmt->executeQuery()->fetchAllAssociative();
}

}
