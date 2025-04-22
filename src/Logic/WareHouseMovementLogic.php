<?php

namespace App\Logic;

use App\Entity\LpWarehouseMovement;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\LpWarehouseMovementDetails;
use App\Entity\LpWarehouseMovementIncidents;
use App\Entity\PsStockAvailable;
use App\Utils\Logger\Logger;
use App\Entity\LpControlStock;

class WareHouseMovementLogic
{

    private $entityManagerInterface;
    private $stockControllLogic;
    private $logger;

    public function __construct(EntityManagerInterface $entityManagerInterface, StockControllLogic $stockControllLogic, Logger $logger)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->stockControllLogic = $stockControllLogic;
        $this->logger = $logger;
    }


    public function generateWareHouseMovementJSON($movement): array
    {
        $movementsJSON = [
            'id_warehouse_movement' => $movement->getIdWarehouseMovement(),
            'date_add' => $movement->getDateAdd()->format('Y-m-d H:i:s'),
            'date_recived' => $movement->getDateRecived() ? $movement->getDateRecived()->format('Y-m-d H:i:s') : null,
            'date_excute' => $movement->getDateExcute() ? $movement->getDateExcute()->format('Y-m-d H:i:s') : null,
            'date_modified' => $movement->getDateModified() ? $movement->getDateModified()->format('Y-m-d H:i:s') : null,
            'description' => $movement->getDescription(),
            'id_shop_origin' => $movement->getIdShopOrigin(),
            'id_shop_destiny' => $movement->getIdShopDestiny(),
            'status' => $movement->getStatus(),
            'type' => $movement->getType(),
            'modify_reason' => $movement->getModifyReason(),
            'employee' => $movement->getIdEmployee(),
            'total_quantity' => $movement->getTotalQuantity(),
        ];
        return $movementsJSON;
    }

    public function generateWareHouseMovementDetailJSON($movement): array
    {
        $movementDetails = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class)->findBy(['id_warehouse_movement' => $movement->getIdWarehouseMovement()]);
        $movementDetailsJSONComplete = [];
        $movementIncidentJSONComplete = [];
        foreach ($movementDetails as $detail) {
            $movementIncidents = $this->entityManagerInterface->getRepository(LpWarehouseMovementIncidents::class)->findBy(['id_warehouse_movement_detail' => $detail->getIdWarehouseMovementDetail()]);
            foreach ($movementIncidents as $movementIncident) {
                $movementIncidentJSON = [
                    'id_incident' => $movementIncident->getIdWarehouseMovementIncidents(),
                    'description' => $movementIncident->getDescription(),
                ];
                $movementIncidentJSONComplete[] = $movementIncidentJSON;
            }

            $movementDetailsJSON = [
                'id_warehouse_movement_detail' => $detail->getIdWarehouseMovementDetail(),
                'id_product' => $detail->getIdProduct(),
                'id_product_attribute' => $detail->getIdProductAttribute(),
                'product_name' => $detail->getProductName(),
                'ean13' => $detail->getEan13(),
                'sent_quantity' => $detail->getSentQuantity(),
                'recived_quantity' => $detail->getRecivedQuantity(),
                'status' => $detail->getStatus(),
                'movement_incidents' => $movementIncidentJSONComplete,
                'id_control_stock' => $detail->getIdControlStock(),
                'stock_origin' => $detail->getStockOrigin(),
                'stock_destiny' => $detail->getStockDestiny()
            ];
            $movementDetailsJSONComplete[] = $movementDetailsJSON;
        }
        return $movementDetailsJSONComplete;
    }

    public function generateWareHouseMovement($data): LpWarehouseMovement
    {
        $newWareHouseMovement = new LpWarehouseMovement();
        $newWareHouseMovement->setDescription($data['description']);
        $newWareHouseMovement->setIdShopOrigin($data['id_shop_origin'] ?? null);
        $newWareHouseMovement->setIdShopDestiny($data['id_shop_destiny'] ?? null);
        $newWareHouseMovement->setStatus('En creacion');
        $newWareHouseMovement->setType($data['type']);
        $newWareHouseMovement->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $newWareHouseMovement->setDateModified(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $newWareHouseMovement->setIdEmployee($data['id_employee']);
        $this->entityManagerInterface->persist($newWareHouseMovement);
        $this->entityManagerInterface->flush();
        return $newWareHouseMovement;
    }

    public function generateWareHouseMovementDetail($detail, $newWareHouseMovement): LpWarehouseMovementDetails
    {
        try{
            $newWareHouseMovementDetail = new LpWarehouseMovementDetails();
            $newWareHouseMovementDetail->setRecivedQuantity($detail['recived_quantity'] ?? null);
            $newWareHouseMovementDetail->setSentQuantity($detail['sent_quantity'] ?? null);
            $newWareHouseMovementDetail->setIdProduct($detail['id_product']);
            $newWareHouseMovementDetail->setIdProductAttribute($detail['id_product_attribute']);
            $newWareHouseMovementDetail->setProductName($detail['product_name']);
            $newWareHouseMovementDetail->setEan13($detail['ean13']);
            $newWareHouseMovementDetail->setIdControlStock($detail['id_control_stock']?? null);
            $newWareHouseMovementDetail->setIdWarehouseMovement($newWareHouseMovement->getIdWarehouseMovement());
            $newWareHouseMovementDetail->setStockDestiny($detail['stock_destiny'] ?? null);
            $newWareHouseMovementDetail->setStockOrigin($detail['stock_origin'] ?? null);
            $newWareHouseMovementDetail->setStatus('creado');
            $this->entityManagerInterface->persist($newWareHouseMovementDetail);
        }catch(\Exception $e){
            throw $e;
        }

        $this->entityManagerInterface->flush();
        return $newWareHouseMovementDetail;
    }

    public function generateWareHouseMovementIncident($incident, $newWareHouseMovementDetail): LpWarehouseMovementIncidents
    {
        $newWareHouseMovementIncident = new LpWarehouseMovementIncidents();
        $newWareHouseMovementIncident->setDescription($incident['description']);
        $newWareHouseMovementIncident->setIdWarehouseMovementDetail($newWareHouseMovementDetail->getIdWarehouseMovementDetail());
        $this->entityManagerInterface->persist($newWareHouseMovementIncident);
        $this->entityManagerInterface->flush();
        return $newWareHouseMovementIncident;
    }

    public function updateWareHouseMovement($data, $movement): LpWarehouseMovement
    {
        $status = $movement->getStatus();

        if ($status == "En creacion") {
            //Se puede modificar cualquier cosa
            $movement->setDescription($data['description']);
            $movement->setIdShopOrigin($data['id_shop_origin'] ?? null);
            $movement->setIdShopDestiny($data['id_shop_destiny'] ?? null);
            $movement->setStatus($data['status']);
            $movement->setType($data['type']);
            $movement->setDateModified(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
            $movement->setModifyReason($data['modify_reason']);

            if (!empty($data['movement_details'])) {
                foreach ($data['movement_details'] as $detail) {
                    $movementDetail = null;
                    if (!empty($detail['id_warehouse_movement_detail'])) {
                        $movementDetail = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class)->find($detail['id_warehouse_movement_detail']);
                    }
                    if (!$movementDetail) {
                        //Generar un nuevo movimiento detail
                        $movementDetail = new LpWarehouseMovementDetails();
                        $movementDetail->setIdWarehouseMovement($movement->getIdWarehouseMovement());
                    }
                    //Sobre escribir el movimiento detail
                    if (isset($detail['sent_quantity'])) {
                        $movementDetail->setSentQuantity($detail['sent_quantity']);
                    }
                    if (isset($detail['recived_quantity'])) {
                        $movementDetail->setRecivedQuantity($detail['recived_quantity']);
                    }
                    if(isset($detail['id_control_stock'])) {
                        $movementDetail->setIdControlStock($detail['id_control_stock']);
                    }
                    if(isset($detail['stock_origin'])) {
                        $movementDetail->setStockOrigin($detail['stock_origin']);
                    }
                    if(isset($detail['stock_destiny'])) {
                        $movementDetail->setStockDestiny($detail['stock_destiny']);
                    }
                    $movementDetail->setIdProduct($detail['id_product']);
                    $movementDetail->setIdProductAttribute($detail['id_product_attribute']);
                    $movementDetail->setProductName($detail['product_name']);
                    $movementDetail->setEan13($detail['ean13']);
                    $movementDetail->setStatus($detail['status']);

                    $this->entityManagerInterface->persist($movementDetail);
                    $this->entityManagerInterface->flush();
                }
            }
        }
        if ($status == "Enviado" || $status == "Recibido") {
            //Solo se puede modificar el estado y date_modified, modify_reason
            $movement->setStatus($data['status']);
            $movement->setDateModified(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
            $movement->setModifyReason($data['modify_reason']);
        }
        if ($status == "En revision" || $status == "Incidencia pendiente") {
            //se puede modificar el estado y movement details y date_modified, modify_reason
            $movement->setStatus($data['status']);
            $movement->setDateModified(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
            $movement->setModifyReason($data['modify_reason']);
            foreach ($data['movement_details'] as $detail) {
                $movementDetail = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class)->find($detail['id_warehouse_movement_detail']);

                //Sobre escribir el movimiento detail
                $movementDetail->setRecivedQuantity($detail['recived_quantity']?? null);
                $movementDetail->setStatus($detail['status']);

                $this->entityManagerInterface->persist($movementDetail);
                $this->entityManagerInterface->flush();
            }
        }
        $this->entityManagerInterface->persist($movement);
        $this->entityManagerInterface->flush();
        return $movement;
    }

    public function executeWareHouseMovement($movement): array
    {
        $this->entityManagerInterface->getConnection()->beginTransaction(); // Start transaction
        try {
            $movement->setStatus('Ejecutado');
            $movement->setDateExcute(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
            $this->entityManagerInterface->persist($movement);
            $movementType = $movement->getType();
            $movementDetails = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class)->findBy(['id_warehouse_movement' => $movement->getIdWarehouseMovement()]);
            $this->logger->log('Executing warehouse movement'.' movement_id: '. $movement->getIdWarehouseMovement());
            foreach ($movementDetails as $detail) {
                $idProduct = $detail->getIdProduct();
                $idProductAttribute = $detail->getIdProductAttribute();
                $sentQuantity = $detail->getSentQuantity();
                $recivedQuantity = $detail->getRecivedQuantity();
                $stockDestiny = $this->entityManagerInterface->getRepository(PsStockAvailable::class)->findOneBy([
                    'id_product' => $idProduct,
                    'id_product_attribute' => $idProductAttribute,
                    'id_shop' => $movement->getIdShopDestiny()
                ]);

                $stockOrigin = $this->entityManagerInterface->getRepository(PsStockAvailable::class)->findOneBy([
                    'id_product' => $idProduct,
                    'id_product_attribute' => $idProductAttribute,
                    'id_shop' => $movement->getIdShopOrigin()
                ]);
                $ean13ControlStockArray[] = [];
                if ($movementType === 'entrada') {
                    // Update stock for destination shop only
                    if ($stockDestiny) {
                        $this->logger->log('Before updating stock for product: '.$idProduct.' product_attribute: '.$idProductAttribute.' shop: '.$movement->getIdShopDestiny().' stock in destiny: '.$stockDestiny->getQuantity());
                        $stockDestiny->setQuantity($stockDestiny->getQuantity() + $recivedQuantity);
                        $this->entityManagerInterface->persist($stockDestiny);
                        $this->logger->log('After updating stock for product: '.$idProduct.' product_attribute: '.$idProductAttribute.' shop: '.$movement->getIdShopDestiny().' stock in destiny: '.$stockDestiny->getQuantity());
                        
                        for ($i = 1; $i <= $recivedQuantity; $i++) {
                            $controllStock = $this->stockControllLogic->createControlStock($idProduct,$idProductAttribute,$movement->getIdShopDestiny(),$detail->getEan13(),false,$detail->getProductName());
                            $this->stockControllLogic->createControlStockHistory($controllStock->getIdControlStock(),'Entrada de producto','Entrada',$movement->getIdShopDestiny(),$detail->getIdWarehouseMovementDetail());
                            $detail->setIdControlStock($controllStock->getIdControlStock());
                            $this->entityManagerInterface->persist($detail);
                            $this->logger->log(
                            ' id_control_stock ' . $controllStock->getIdControlStock()
                            . ' id_product ' . $controllStock->getIdProduct()
                            . ' id_product_attribute ' . $controllStock->getIdProductAtributte()
                            . ' id_shop ' . $controllStock->getIdShop()
                            . ' ean13 ' . $controllStock->getEan13()
                            . ' reason ' . 'Entrada de producto'
                            . ' type ' . 'Entrada'
                            . ' date ' . (new \DateTime('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d H:i:s')
                        );

                        $ean13ControlStockArray[] = [
                            'ean13' => $controllStock->getEan13(),
                            'control_stock' => $controllStock->getIdControlStock()
                        ];

                        }
                    }
                } elseif ($movementType === 'salida') {
                    // Update stock for origin shop only

                    if ($stockOrigin) {
                        $this->logger->log('Before updating stock for product: '.$idProduct.' product_attribute: '.$idProductAttribute.' shop: '.$movement->getIdShopOrigin().' stock in origin: '.$stockOrigin->getQuantity());
                        $stockOrigin->setQuantity($stockOrigin->getQuantity() - $sentQuantity);
                        $this->entityManagerInterface->persist($stockOrigin);
                        $this->logger->log('After updating stock for product: '.$idProduct.' product_attribute: '.$idProductAttribute.' shop: '.$movement->getIdShopOrigin().' stock in origin: '.$stockOrigin->getQuantity());
                        $controlStock = null;
                        if($detail->getIdControlStock() != null)
                            $controlStock = $this->entityManagerInterface->getRepository(LpControlStock::class)->find($detail->getIdControlStock());
                        if ($controlStock != null) {
                            $controlStock->setActive(false);
                            $controlStock->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
                            $this->entityManagerInterface->persist($controlStock);
                            $this->stockControllLogic->createControlStockHistory($detail->getIdControlStock(),'Salida de producto','Salida', $movement->getIdShopOrigin(),$detail->getIdWarehouseMovementDetail());
                        }
                    }
                } elseif ($movementType === 'traspaso') {
                    // Update stock for both origin and destination shops
                    if ($stockOrigin) {
                        $this->logger->log('Before updating stock for product: '.$idProduct.' product_attribute: '.$idProductAttribute.' shop: '.$movement->getIdShopOrigin().' stock in origin: '.$stockOrigin->getQuantity());
                        $stockOrigin->setQuantity($stockOrigin->getQuantity() - $sentQuantity);
                        $this->entityManagerInterface->persist($stockOrigin);
                        $this->logger->log('After updating stock for product: '.$idProduct.' product_attribute: '.$idProductAttribute.' shop: '.$movement->getIdShopOrigin().' stock in origin: '.$stockOrigin->getQuantity());
                    }
                    if ($stockDestiny) {
                        $this->logger->log('Before updating stock for product: '.$idProduct.' product_attribute: '.$idProductAttribute.' shop: '.$movement->getIdShopDestiny().' stock in destiny: '.$stockDestiny->getQuantity());
                        $stockDestiny->setQuantity($stockDestiny->getQuantity() + $sentQuantity);
                        $this->entityManagerInterface->persist($stockDestiny);
                        $this->logger->log('After updating stock for product: '.$idProduct.' product_attribute: '.$idProductAttribute.' shop: '.$movement->getIdShopDestiny().' stock in destiny: '.$stockDestiny->getQuantity());
                    }
                    if($detail->getIdControlStock() != null)
                    {
                        $controlStock = $this->entityManagerInterface->getRepository(LpControlStock::class)->find($detail->getIdControlStock());
                        if ($controlStock) {
                            $controlStock->setIdShop($movement->getIdShopDestiny());
                            $controlStock->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
                            $this->entityManagerInterface->persist($controlStock);
                            $this->stockControllLogic->createControlStockHistory($detail->getIdControlStock(),'Traspaso de producto','Traspaso',$movement->getIdShopDestiny(),$detail->getIdWarehouseMovementDetail());
                        }
                    }
                }
            }

            $this->entityManagerInterface->flush();
            $this->entityManagerInterface->getConnection()->commit(); // Commit transaction
        } catch (\Exception $e) {
            $this->entityManagerInterface->getConnection()->rollBack(); // Rollback transaction
            throw $e;
        }
        return [
            'movement' => $movement,
            'ean13_control_stock' => $ean13ControlStockArray ?? []
        ];
    }

    public function sumTotalQuantity($type ,$detail ,$total_quantity):int
    {
        if ($type == 'entrada') {
            if ($detail['recived_quantity'] != null) {
                $total_quantity = $detail['recived_quantity'];
            }
        } elseif ($type == 'salida' || $type == 'traspaso') {
            if ($detail['sent_quantity'] != null) {
                $total_quantity = $detail['sent_quantity'];
            }
        }

        return $total_quantity;
    }

    public function setTotalQuantity($movement,$total_quantity)
    {
        $movement->setTotalQuantity($total_quantity);
        $this->entityManagerInterface->persist($movement);
        $this->entityManagerInterface->flush();
    }
}