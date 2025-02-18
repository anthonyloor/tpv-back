<?php

namespace App\Logic;

use App\Entity\LpControlStock;
use App\Entity\LpControlStockHistory;

use Doctrine\Persistence\ManagerRegistry;
use App\Utils\Logger\Logger;


class StockControllLogic
{
    private $entityManagerInterface;
    private $emFajasMaylu;
    private $logger;
    public function __construct(ManagerRegistry $doctrine, Logger $logger)
    {
        $this->entityManagerInterface = $doctrine->getManager('default');
        $this->emFajasMaylu = $doctrine->getManager('fajas_maylu');
        $this->logger = $logger;
    }

    public function createControlStock($idProduct, $idProductAttribute, $idShop, $ean13): LpControlStock
    {
        $controlStock = new LpControlStock();
        $controlStock->setIdProduct($idProduct);
        $controlStock->setIdProductAtributte($idProductAttribute);
        $controlStock->setIdShop($idShop);
        $controlStock->setDateAdd(new \DateTime());
        $controlStock->setEan13($ean13);
        $this->entityManagerInterface->persist($controlStock);
        $this->entityManagerInterface->flush();
        $this->logger->log('Control stock created with id_control_stock ' . $controlStock->getIdControlStock()
            . ' id_product ' . $controlStock->getIdProduct()
            . ' id_product_attribute ' . $controlStock->getIdProductAtributte()
            . ' id_shop ' . $controlStock->getIdShop());
        return $controlStock;
    }

    public function createControlStockHistory($idControlStock, $reason, $type): void
    {
        if ($idControlStock != null) {
            $controlStockHistory = new LpControlStockHistory();
            $controlStockHistory->setIdControlStock($idControlStock);
            $controlStockHistory->setReason($reason);
            $controlStockHistory->setType($type);
            $controlStockHistory->setDate(new \DateTime());
            $this->entityManagerInterface->persist($controlStockHistory);
            $this->entityManagerInterface->flush();
            $this->logger->log('Control stock history created with id_control_stock_history ' . $controlStockHistory->getIdControlStockHistory()
                . ' id_control_stock ' . $controlStockHistory->getIdControlStock()
                . ' reason ' . $controlStockHistory->getReason()
                . ' type ' . $controlStockHistory->getType()
                . ' date ' . $controlStockHistory->getDate()->format('Y-m-d H:i:s'));
        }
    }



}
