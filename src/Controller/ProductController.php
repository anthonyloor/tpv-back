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
        nombre_producto.name as product_name,
        GROUP_CONCAT(DISTINCT nombre_combinacion.name ORDER BY nombre_combinacion.idAttribute SEPARATOR \' - \') AS combination_name,
        combinacion.reference as reference_combination,
        combinacion.ean13 as ean13_combination,
        ROUND(product.price, 2) AS price,
        stock.quantity,
        tienda.name AS shop_name,
        CONCAT(\'https://mayret.com/img/p/\', 
           SUBSTRING(image.id_image, 1, 1), \'/\', 
           SUBSTRING(image.id_image, 2, 1), \'/\', 
           SUBSTRING(image.id_image, 3, 1), \'/\', 
           SUBSTRING(image.id_image, 4, 1), \'/\', 
           SUBSTRING(image.id_image, 5, 1), \'/\', 
           image.id_image, \'.jpg\') AS image_url
    ')
            ->from(PsStockAvailable::class, 'stock')
            ->innerJoin('stock.id_product', 'product')
            ->innerJoin(PsProductLang::class, 'nombre_producto', 'WITH', 'product.id_product = nombre_producto.id_product')
            ->innerJoin(PsProductAttribute::class, 'combinacion', 'WITH', 'stock.id_product_attribute = combinacion.id_product_attribute')
            ->innerJoin(PsProductAttributeCombination::class, 'combi', 'WITH', 'combinacion.id_product_attribute = combi.id_product_attribute')
            ->innerJoin(PsAttributeLang::class, 'nombre_combinacion', 'WITH', 'combi.idAttribute = nombre_combinacion.idAttribute')
            ->innerJoin(PsShop::class, 'tienda', 'WITH', 'stock.id_shop = tienda.id_shop')
            ->innerJoin(PsProductAttributeImage::class, 'product_image', 'WITH', 'combinacion.id_product_attribute = product_image.id_product_attribute')
            ->innerJoin(PsImage::class, 'image', 'WITH', 'product_image.id_image = image.id_image')
            ->groupBy('product.id_product, combinacion.id_product_attribute, tienda.id_shop')
            ->orderBy('product.id_product', 'DESC')
            ->addOrderBy('combinacion.id_product_attribute')
            ->addOrderBy('tienda.id_shop', 'DESC')
            ->andWhere('combinacion.reference LIKE :searchTerm OR combinacion.ean13 LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $b . '%');

        $resultado = $qb->getQuery()->getResult();

        foreach ($resultado as &$row) {
            $row['price'] = (float) $row['price'];
        }

        return new JsonResponse($resultado);

    }
}
