<?php

namespace App\RepositoryFajasMaylu;

use App\EntityFajasMaylu\PsStockAvailable;
use App\EntityFajasMaylu\PsProductLang;

use App\EntityFajasMaylu\PsAttributeLang;
use App\EntityFajasMaylu\PsProductAttributeCombination;
use App\EntityFajasMaylu\PsShop;
use App\EntityFajasMaylu\PsProductShop;
use App\EntityFajasMaylu\PsCategoryLang;
use App\EntityFajasMaylu\PsCategory;
use App\Entity\LpControlStock;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PsStockAvailableFajasMayluRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PsStockAvailable::class);
        $this->em = $registry->getManager('fajas_maylu');
    }

    public function findOneByProductAttributeShop($idProduct, $idProductAttributte, $idShop):?PsStockAvailable
    {
        return $this->em->createQueryBuilder()
        ->select('p')
        ->from(PsStockAvailable::class, 'p')
        ->andWhere('p.id_product = :idProduct')
        ->andWhere('p.id_product_attribute = :idProductAttribute')
        ->andWhere('p.id_shop = :shop')
        ->setParameter('idProduct', $idProduct)
        ->setParameter('idProductAttribute', $idProductAttributte)
        ->setParameter('shop', $idShop)
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function findByAttribute(string $searchTerm, mixed $value, string $id_shop): array  
    {
        $allowedFields = ['ean13', 'reference', 'all'];

        if (!in_array($searchTerm, $allowedFields, true)) {
            throw new \InvalidArgumentException("Campo de búsqueda no válido.");
        }

        

        $queryBuilder = $this->em->createQueryBuilder()
            ->select("
        p.id_product AS id_product,
        pa.id_product_attribute AS id_product_attribute,
        sav.id_stock_available AS id_stock_available,
        shop.id_shop AS id_shop,
        pl.name AS product_name,
        GROUP_CONCAT(DISTINCT al.name ORDER BY al.idAttribute SEPARATOR ' - ') AS combination_name,
        p.reference AS reference_combination,
        cl.name AS name_category,
        pa.ean13 AS ean13_combination,
        NULLIF(p.ean13, :empty) AS ean13_combination_0,
        sa.price AS price,
        sav.quantity AS quantity,
        shop.name AS shop_name,
        pl.linkRewrite AS link_rewrite,
        sa.active AS active
        ")
            ->from(PsStockAvailable::class, 'sav')
            ->innerJoin('sav.id_product', 'p')
            ->leftJoin('sav.id_product_attribute', 'pa')
            ->leftJoin(PsProductAttributeCombination::class, 'pac', 'WITH', 'sav.id_product_attribute = pac.id_product_attribute')
            ->innerJoin(PsProductLang::class, 'pl', 'WITH', 'sav.id_product = pl.id_product AND pl.id_lang = 1')
            ->leftJoin(PsAttributeLang::class, 'al', 'WITH', 'pac.idAttribute = al.idAttribute AND al.id_lang = 1')
            ->innerJoin(PsProductShop::class, 'sa', 'WITH', 'sav.id_product = sa.id_product')
            ->innerJoin(PsShop::class, 'shop', 'WITH', 'sav.id_shop = shop.id_shop')
            ->leftJoin(PsCategoryLang::class, 'cl', 'WITH', 'sa.id_category_default = cl.id_category AND cl.id_lang = 1 AND cl.id_shop = 1')
            ->leftJoin(PsCategory::class, 'c', 'WITH', 'c.id_category = cl.id_category')
            //->leftJoin(LpControlStock::class, 'lcs', 'WITH', 'sav.id_shop = lcs.id_shop AND sav.id_product = lcs.id_product AND sav.id_product_attribute = lcs.id_product_attribute')
            //->addSelect('lcs.id_control_stock AS id_control_stock')
            //->groupBy('sav.id_product, sav.id_product_attribute, sav.id_shop, lcs.id_control_stock')
            ->groupBy('sav.id_product, sav.id_product_attribute, sav.id_shop')
            ->orderBy('p.id_product', 'DESC')
            ->setParameter('empty', '');


        if ($searchTerm == 'ean13') {
            $queryBuilder->where('pa.' . $searchTerm . ' = :value' . ' OR p.' . $searchTerm . ' = :value')
                ->setParameter('value', $value);
        }
        if ($searchTerm == 'reference') {
            $queryBuilder->where('p.' . $searchTerm . ' = :value')
                ->setParameter('value', $value);
        }
        if(isset($id_shop)){
            $queryBuilder->andWhere('sav.id_shop = :id_shop')
                ->setParameter('id_shop', $id_shop);
        }


        return $queryBuilder->getQuery()->getResult();
    }
}



