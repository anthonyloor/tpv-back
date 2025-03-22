<?php

namespace App\Controller;

use App\Entity\PsProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


use App\Entity\PsProductLang;
use App\Entity\PsAttributeLang;
use App\Entity\PsProductAttributeCombination;
use App\Entity\PsStockAvailable;
use App\Entity\PsShop;
use App\Entity\PsProductShop;
use App\Entity\PsCategoryLang;
use App\Entity\PsCategory;
use App\Entity\LpControlStock;

use App\Logic\ProductLogic;
use App\Logic\StockControllLogic;

class ProductController extends AbstractController
{

  private $entityManagerInterface;
  private $productLogic;
  private $controlStockLogic;
  public function __construct(EntityManagerInterface $entityManagerInterface, ProductLogic $productLogic, StockControllLogic $controlStockLogic)
  {
    $this->entityManagerInterface = $entityManagerInterface;
    $this->productLogic = $productLogic;
    $this->controlStockLogic = $controlStockLogic;
  }

  #[Route('/product_search', name: 'product_search')]
  public function productSearch(): Response
  {
    $b = $_GET['b'];

    $qb = $this->entityManagerInterface->createQueryBuilder();

    $qb->select("
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
    pl.linkRewrite AS link_rewrite
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
      ->leftJoin(LpControlStock::class, 'lcs', 'WITH', 'sav.id_shop = lcs.id_shop AND sav.id_product = lcs.id_product AND sav.id_product_attribute = lcs.id_product_attribute')
      ->addSelect('lcs.id_control_stock AS id_control_stock, lcs.active as active_control_stock')
      ->where('(p.reference = :searchTerm OR pa.ean13 = :searchTerm2 OR p.ean13 = :searchTerm2)')
      ->setParameter('empty', value: '')
      ->setParameter('searchTerm', $b)
      ->setParameter('searchTerm2', $b)
      ->groupBy('sav.id_product, sav.id_product_attribute, sav.id_shop, lcs.id_control_stock')
      ->orderBy('p.id_product', 'DESC');
    $resultado = $qb->getQuery()->getResult();

    foreach ($resultado as &$row) {
      $row['price'] = (float) number_format((float) $row['price'] * 1.21, 2, '.', '');
    }

    return new JsonResponse($resultado);

  }

  #[Route('/get_product_price_tag', name: 'get_product_price_tag')]
  public function getProductPriceTag(Request $request): Response
  {
    $data = json_decode($request->getContent(), true);
    $response = [];
    if (isset($data['id_control_stock'])) {
      $idControlStock = $data['id_control_stock'];
      $lpControlStock = $this->entityManagerInterface->getRepository(LpControlStock::class)->findOneBy(['id_control_stock' => $idControlStock, 'ean13' => $data['ean13']]);
      if (!$lpControlStock) {
        return new JsonResponse(['error' => 'No se ha encontrado identificador unico'], Response::HTTP_NOT_FOUND);
      }else{
        $response = $this->controlStockLogic->generateControlStockJSON($lpControlStock);
        $lpControlStock->setPrinted(true);
        $lpControlStock->setActive(true);
        $this->entityManagerInterface->persist($lpControlStock);
      }
    } else {
      if(!$this->controlStockLogic->controlMaxPriceTags($data['ean13'],$data['quantity'],$data['quantity_print'],$data['id_shop'])){
        $response = ['error' => 'Se ha superado la cantidad maxima de etiquetas o no se puede imprimir esa cantidad para este producto'];
      }else{
        $response['tags'] = [];
        for ($i = 0; $i < $data['quantity_print']; $i++) {
          $lpControlStock = $this->controlStockLogic->createControlStock($data['id_product'], $data['id_product_attribute'], $data['id_shop'], $data['ean13'], true);
          $this->controlStockLogic->createControlStockHistory($lpControlStock->getIdControlStock(),'Se añade seguimiento al reimprimir','Reimpresion',$data['id_shop']);

          $response['tags'][] = $this->controlStockLogic->generateControlStockJSON($lpControlStock);
          $this->entityManagerInterface->persist($lpControlStock);
        }
      }
    }
    $this->entityManagerInterface->flush();

    return new JsonResponse($response);
  }

  #[Route('/get_stock_report', name: 'get_stock_report')]
  public function getStockReport(Request $request):Response
  {
    $data = json_decode($request->getContent(), true);
    if(!isset($data['license'], $data['search_term'])){
      return new JsonResponse(['error' => 'Faltan parametros'], Response::HTTP_BAD_REQUEST);
    }
    
    $products = $this->entityManagerInterface->getRepository(PsStockAvailable::class)->findByAttribute($data['search_term'], $data['value'], $data['id_shop']);
    $productsJSON = $this->productLogic->generateProductStockReportJSON($products);
    return new JsonResponse($productsJSON);
  }

  #[Route('/generate_ean13', name: 'generate_ean13')]
  public function generateEan13(Request $request):Response
  {
    $data = json_decode($request->getContent(), true);
    if(!isset($data['products'])){
      return new JsonResponse(['error' => 'Faltan parametros'], Response::HTTP_BAD_REQUEST);
    }
    $ean13List = $this->controlStockLogic->generateEan13List($data['products']);
    return new JsonResponse(['ean13' => $ean13List]);
  }
}
