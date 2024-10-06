<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


use App\Entity\PsProduct;
use App\Entity\PsProductLang;
use App\Entity\PsProductAttribute;
use App\Entity\PsAttributeLang;
use App\Entity\PsProductAttributeCombination;
use App\Entity\PsStockAvailable;
use App\Entity\PsShop;
use App\Entity\PsSpecificPrice;

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
            stock.idStockAvailable AS ID_STOCK,
            product.idProduct AS ID_PRODUCTO,
            combinacion.idProductAttribute AS ID_COMBINACION,
            tienda.idShop AS ID_TIENDA,
            tienda.name AS TIENDA,
            product.reference AS REFERENCIA,
            GROUP_CONCAT(DISTINCT nombre_combinacion.name ORDER BY nombre_combinacion.idAttribute SEPARATOR \', \') AS COMBINACION_TALLA_COLOR,
            stock.quantity AS CANTIDAD,
            nombre_producto.name AS NOMBRE,
            combinacion.ean13 AS EAN13,
            CASE
                WHEN oferta.reduction IS NOT NULL AND oferta.idGroup != 5 AND oferta.reductionType = \'amount\' THEN ROUND(( ( product.price - oferta.reduction ) * 1.21 ), 2 )
                WHEN oferta.reduction IS NOT NULL AND oferta.idGroup != 5 AND oferta.reductionType = \'percentage\' THEN ROUND( ( ( product.price -( product.price * oferta.reduction / 100 ) ) * 1.21 ), 2 )
                ELSE ROUND( (product.price * 1.21) +(combinacion.price * 1.21), 2 )
            END AS PRECIO_CON_IVA
        ')
        ->from(PsStockAvailable::class, 'stock')
        ->innerJoin('stock.idProduct', 'product')  
        ->innerJoin(PsProductLang::class, 'nombre_producto', 'WITH', 'product.idProduct = nombre_producto.idProduct')
        ->innerJoin(PsProductAttribute::class, 'combinacion', 'WITH', 'stock.idProductAttribute = combinacion.idProductAttribute')
        ->innerJoin(PsProductAttributeCombination::class, 'combi', 'WITH', 'combinacion.idProductAttribute = combi.idProductAttribute')
        ->innerJoin(PsAttributeLang::class, 'nombre_combinacion', 'WITH', 'combi.idAttribute = nombre_combinacion.idAttribute')
        ->innerJoin(PsShop::class, 'tienda', 'WITH', 'stock.idShop = tienda.idShop')
        ->leftJoin(PsSpecificPrice::class, 'oferta', 'WITH', 'product.idProduct = oferta.idProduct')
        ->groupBy('ID_PRODUCTO, ID_COMBINACION, ID_TIENDA')
        ->orderBy('ID_PRODUCTO', 'DESC')
        ->addOrderBy('ID_COMBINACION')
        ->addOrderBy('ID_TIENDA', 'DESC')
        ->andWhere('tienda.idShop = 9')
        ->andWhere('product.reference LIKE :searchTerm OR combinacion.ean13 LIKE :searchTerm')
        ->setParameter('searchTerm', '%' . $b . '%');
            
        $result = $qb->getQuery()->getResult();

        return new JsonResponse($result);

    }
}
