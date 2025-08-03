<?php

namespace App\Logic;

use App\Entity\LpControlStock;
use App\Entity\LpControlStockHistory;
use App\Entity\PsProductAttribute;

use App\Entity\PsProduct;
use App\Entity\PsShop;
use App\Entity\PsStockAvailable;
use Doctrine\Persistence\ManagerRegistry;
use App\Utils\Logger\Logger;


class StockControllLogic
{
    private $entityManagerInterface;
    private $logger;
    public function __construct(ManagerRegistry $doctrine, Logger $logger)
    {
        $this->entityManagerInterface = $doctrine->getManager('default');
        $this->logger = $logger;
    }

    public function createControlStock($idProduct, $idProductAttribute, $idShop, $ean13,$printed = false, $productName): LpControlStock
    {
        $controlStock = new LpControlStock();
        $controlStock->setIdProduct($idProduct);
        $controlStock->setIdProductAtributte($idProductAttribute);
        $controlStock->setIdShop($idShop);
        $controlStock->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $controlStock->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $controlStock->setActive(true);
        $controlStock->setPrinted($printed);
        $controlStock->setEan13($ean13);
        $controlStock->setProductName($productName);
        $this->entityManagerInterface->persist($controlStock);
        $this->entityManagerInterface->flush();
        $this->logger->log('Control stock created with id_control_stock ' . $controlStock->getIdControlStock()
            . ' id_product ' . $controlStock->getIdProduct()
            . ' id_product_attribute ' . $controlStock->getIdProductAtributte()
            . ' id_shop ' . $controlStock->getIdShop());
        return $controlStock;
    }

    public function createControlStockHistory($idControlStock, $reason, $type,$idShop,$transaction_detail_id = null): void
    {
        if ($idControlStock != null) {
            $controlStockHistory = new LpControlStockHistory();
            $controlStockHistory->setIdControlStock($idControlStock);
            $controlStockHistory->setIdShop($idShop);
            $controlStockHistory->setReason($reason);
            $controlStockHistory->setType($type);
            $controlStockHistory->setDate(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
            if($transaction_detail_id != null) {
                $controlStockHistory->setIdTransactionDetail($transaction_detail_id);
            }
            $this->entityManagerInterface->persist($controlStockHistory);
            $this->entityManagerInterface->flush();
            $this->logger->log('Control stock history created with id_control_stock_history '. $controlStockHistory->getIdControlStockHistory());
        }

    }

    public function generateControlStockJSON($controlStock): array
    {
        $orderData = [
            'id_control_stock' => $controlStock->getIdControlStock(),
            'id_product' => $controlStock->getIdProduct(),
            'id_product_attribute' => $controlStock->getIdProductAtributte(),
            'id_shop' => $controlStock->getIdShop(),
            'date_add' => $controlStock->getDateAdd()->format('Y-m-d H:i:s'),
            'ean13' => $controlStock->getEan13(),
            'active' => $controlStock->getActive(),
            'printed' => $controlStock->getPrinted()
        ];
        return $orderData;
    }

    public function controlMaxPriceTags($ean13,$quantity,$quantityPrint,$idShop):bool
    {
        $repository = $this->entityManagerInterface->getRepository(LpControlStock::class);
        $controlStocks = $repository->findBy(['ean13' => $ean13, 'active' => true, 'printed' => true, 'id_shop' => $idShop]);
        if (count($controlStocks) + $quantityPrint > $quantity) {
            return false;
        }
        return true;
    }

    public function generateEan13List($products): array
    {
        $shops = $this->entityManagerInterface->getRepository(PsShop::class)->findAll();
        $ean13 = [];
        foreach ($products as $product) {
            if ($product['id_product_attribute'] != null) 
            {
                $productAttribute = $this->entityManagerInterface->getRepository(PsProductAttribute::class)
                    ->findOneBy(['idProduct' => $product['id_product'], 'id_product_attribute' => $product['id_product_attribute']]);
                $productAttribute->setEan13($this->generateEan13());
                $this->entityManagerInterface->persist($productAttribute);
            } else {
                $product = $this->entityManagerInterface->getRepository(PsProduct::class)->findOneBy(['id_product' => $product['id_product']]);
                $product->setEan13($this->generateEan13());
                $this->entityManagerInterface->persist($product);
            }

            $this->entityManagerInterface->flush();
            $ean13[] = [
                'ean13' => $productAttribute ? $productAttribute->getEan13() : $product->getEan13(),
                'id_product' => $product['id_product'],
                'id_product_attribute' => $product['id_product_attribute'] ?? null
            ];

            foreach ($shops as $shop) 
            {
                $stockAvailable = $this->entityManagerInterface->getRepository(PsStockAvailable::class)
                    ->findOneBy([
                        'id_product' => $product['id_product'],
                        'id_product_attribute' => $product['id_product_attribute'],
                        'id_shop' => $shop->getIdShop()
                    ]);

                if (!$stockAvailable) 
                {
                    $newStockAvailable = new PsStockAvailable();
                    $productEntity = $this->entityManagerInterface->getRepository(PsProduct::class)->findOneById($product['id_product']);
                    $newStockAvailable->setIdProduct($productEntity);
                    $productAttribute = $this->entityManagerInterface->getRepository(PsProductAttribute::class)->findOneBy(['idProduct' => $product['id_product'], 'id_product_attribute' => $product['id_product_attribute']]);
                    $newStockAvailable->setIdProductAttribute($productAttribute);
                    $newStockAvailable->setIdShop($shop);
                    $newStockAvailable->setQuantity(0); // Set initial quantity to 0 or any default value
                    $newStockAvailable->setPhysicalQuantity(0);
                    $newStockAvailable->setReservedQuantity(0);
                    $newStockAvailable->setDependsOnStock(false); 
                    $newStockAvailable->setOutOfStock(false);
                    $newStockAvailable->setLocation(''); // Set location or any default value
                    $this->entityManagerInterface->persist($newStockAvailable);
                    $this->entityManagerInterface->flush();
                }
            }
        }
        return $ean13;
    }

    public function generateEan13(): string
    {
        do {
            $ean13 = '';
            for ($i = 0; $i < 13; $i++) {
                if ($i === 0) {
                    $ean13 .= rand(1, 9); // Ensure the first character is not 0
                } else {
                    $ean13 .= rand(0, 9);
                }
            }

            $existsInProduct = $this->entityManagerInterface->getRepository(PsProduct::class)
                ->findOneBy(['ean13' => $ean13]);

            $existsInProductAttribute = $this->entityManagerInterface->getRepository(PsProductAttribute::class)
                ->findOneBy(['ean13' => $ean13]);

        } while ($existsInProduct || $existsInProductAttribute);

        return $ean13;
    }

    public function updateProductsStock($products): void
    {
        foreach ($products as $product) {
            $stockAvailable = $this->entityManagerInterface->getRepository(PsStockAvailable::class)
                ->findOneByProductAttributeShop(
                    $product['id_product'],
                    $product['id_product_attribute'],
                    13
                );

            // Si existe, reducir el stock disponible en función de la cantidad de pedido
            if ($stockAvailable) {
                $newQuantity = $stockAvailable->getQuantity() - $product['quantity'];
                $stockAvailable->setQuantity($newQuantity);
                $this->entityManagerInterface->persist($stockAvailable); // Persistir los cambios
            }
        }
    }

    public function generateControlStockJSONComplete($controlStock): array
    {
        $orderData = [
            'id_control_stock' => $controlStock->getIdControlStock(),
            'id_product' => $controlStock->getIdProduct(),
            'id_product_attribute' => $controlStock->getIdProductAtributte(),
            'id_shop' => $controlStock->getIdShop(),
            'date_add' => $controlStock->getDateAdd()->format('Y-m-d H:i:s'),
            'ean13' => $controlStock->getEan13(),
            'active' => $controlStock->getActive(),
            'printed' => $controlStock->getPrinted(),
            'product_name' => $controlStock->getProductName()
        ];
        return $orderData;
    }

    public function generateControlStockHistoryJSON($controlStock): array
    {
        $history = $this->entityManagerInterface->getRepository(LpControlStockHistory::class)->findBy(['id_control_stock' => $controlStock->getIdControlStock()]);
        $historyJSON = [];
        foreach ($history as $h) {
            $historyJSON[] = [
                'id_control_stock_history' => $h->getIdControlStockHistory(),
                'id_control_stock' => $h->getIdControlStock(),
                'id_shop' => $h->getIdShop(),
                'reason' => $h->getReason(),
                'type' => $h->getType(),
                'date' => $h->getDate()->format('Y-m-d H:i:s'),
                'id_transaction_detail' => $h->getIdTransactionDetail(),
            ];
        }
        return $historyJSON;
    }

}
