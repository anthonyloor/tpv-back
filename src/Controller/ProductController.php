<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


use App\Entity\PsProductLang;
use App\Entity\PsProductAttribute;
use App\Entity\PsAttributeLang;
use App\Entity\PsProductAttributeCombination;
use App\Entity\PsStockAvailable;
use App\Entity\PsShop;
use App\Entity\PsProductAttributeImage;
use App\Entity\PsImage;
use App\Entity\PsSpecificPrice;
use App\Entity\PsProductShop;


class ProductController extends AbstractController
{

    private $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/product_search', name: 'product_search')]
    public function productSearch(): Response
    {
        $b = $_GET['b'];

        $qb = $this->entityManagerInterface->createQueryBuilder();
        $qb->select('
        product.id_product,
        combinacion.id_product_attribute,
        stock.id_stock_available,
        tienda.id_shop,
        nombre_producto.name AS product_name,
        GROUP_CONCAT(DISTINCT nombre_combinacion.name ORDER BY nombre_combinacion.idAttribute SEPARATOR \' - \') AS combination_name,
        combinacion.reference AS reference_combination,
        combinacion.ean13 AS ean13_combination,
        ROUND(product.price * 1.21, 2) AS price_incl_tax,
        stock.quantity,
        tienda.name AS shop_name,
        CONCAT(\'https://mayret.com/img/p/\', 
           SUBSTRING(image.id_image, 1, 1), \'/\', 
           SUBSTRING(image.id_image, 2, 1), \'/\', 
           SUBSTRING(image.id_image, 3, 1), \'/\', 
           SUBSTRING(image.id_image, 4, 1), \'/\', 
           SUBSTRING(image.id_image, 5, 1), \'/\', 
           image.id_image, \'.jpg\') AS image_url,
        CASE 
          WHEN specific_price.id_specific_price IS NOT NULL THEN 
            CASE 
              WHEN specific_price.price >= 0 THEN ROUND(specific_price.price * 1.21, 2)
              ELSE 
                CASE 
                  WHEN specific_price.reductionType = \'amount\' THEN ROUND((product.price - specific_price.reduction) * 1.21, 2)
                  WHEN specific_price.reductionType = \'percentage\' THEN ROUND((product.price * (1 - specific_price.reduction)) * 1.21, 2)
                  ELSE ROUND(product.price * 1.21, 2)
                END
            END
        ELSE 
          ROUND(product.price * 1.21, 2)
        END AS final_price_incl_tax
    ')
    ->from(PsStockAvailable::class, 'stock')
    ->innerJoin('stock.id_product', 'product')
    ->innerJoin(PsProductLang::class, 'nombre_producto', 'WITH', 'product.id_product = nombre_producto.id_product AND nombre_producto.id_lang = 1')
    ->innerJoin(PsProductAttribute::class, 'combinacion', 'WITH', 'stock.id_product_attribute = combinacion.id_product_attribute')
    ->innerJoin(PsProductAttributeCombination::class, 'combi', 'WITH', 'combinacion.id_product_attribute = combi.id_product_attribute')
    ->innerJoin(PsAttributeLang::class, 'nombre_combinacion', 'WITH', 'combi.idAttribute = nombre_combinacion.idAttribute')
    ->innerJoin(PsProductShop::class, 'product_shop', 'WITH', 'stock.id_product = product_shop.id_product')
    ->innerJoin(PsShop::class, 'tienda', 'WITH', 'stock.id_shop = tienda.id_shop')
    ->leftJoin(PsProductAttributeImage::class, 'product_image', 'WITH', 'combinacion.id_product_attribute = product_image.id_product_attribute')
    ->leftJoin(PsImage::class, 'image', 'WITH', 'product_image.id_image = image.id_image')
    ->leftJoin(PsSpecificPrice::class, 'specific_price', 'WITH', '
        specific_price.id_product = product.id_product 
        AND (specific_price.id_product_attribute = 0 OR specific_price.id_product_attribute = combinacion.id_product_attribute)
        AND specific_price.id_customer = 0
        AND (specific_price.id_group = 7 OR specific_price.id_group = 0)
        AND specific_price.fromQuantity <= 1
    ')
    ->groupBy('product.id_product, combinacion.id_product_attribute, tienda.id_shop')
    ->orderBy('product.id_product', 'DESC')
    ->addOrderBy('combinacion.id_product_attribute')
    ->addOrderBy('tienda.id_shop', 'DESC')
    ->andWhere('combinacion.reference LIKE :searchTerm OR combinacion.ean13 = :searchTerm2')
    ->setParameter('searchTerm', '%' . $b . '%')
    ->setParameter('searchTerm2', $b);
    

        $resultado = $qb->getQuery()->getResult();

        foreach ($resultado as &$row) {
            $row['price_incl_tax'] = (float) $row['price_incl_tax'];
            $row['final_price_incl_tax'] = (float) $row['final_price_incl_tax'];

        }

        return new JsonResponse($resultado);

    }
}
