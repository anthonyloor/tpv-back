<?php

namespace App\Logic;

use App\Entity\LpControlStock;
use Doctrine\ORM\EntityManagerInterface;


class ProductLogic
{
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }


    public function generateProductStockReportJSON($products)
    {
        $productArray = [];
        foreach ($products as $product) {
            $productArray[] = [
                'id_product' => $product['id_product'],
                'id_product_attribute' => $product['id_product_attribute'],
                'id_stock_available' => $product['id_stock_available'],
                'id_shop' => $product['id_shop'],
                'product_name' => $product['product_name'],
                'combination_name' => $product['combination_name'],
                'reference_combination' => $product['reference_combination'],
                'name_category' => $product['name_category'],
                'ean13_combination' => $product['ean13_combination'],
                'ean13_combination_0' => $product['ean13_combination_0'],
                'price' => (float) number_format((float) $product['price'] * 1.21, 2, '.', ''),
                'quantity' => $product['quantity'],
                'control_stock' => $this->controlStock(
                    $product['id_product'],
                    $product['id_product_attribute'],
                    $product['id_shop']
                )
            ];
        }
        return $productArray;
    }

    public function controlStock($idProduct, $idProductAttribute, $idShop): string
    {
        $controlStocks = $this->entityManagerInterface->getRepository(LpControlStock::class)->findBy([
            'id_product' => $idProduct,
            'id_product_attribute' => $idProductAttribute,
            'id_shop' => $idShop,
            'active' => true,
            'printed' => true,
        ]);

        if (empty($controlStocks)) {
            return '';
        }

        $ids = array_map(static function (LpControlStock $controlStock) {
            return $controlStock->getIdControlStock();
        }, $controlStocks);

        return implode(';', $ids);
    }
}