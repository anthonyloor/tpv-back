<?php

namespace App\Logic;

use App\Entity\LpWarehouseMovement;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\LpWarehouseMovementDetails;
use App\Entity\LpWarehouseMovementIncidents;

class WareHouseMovementLogic
{

    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
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
                'product_name' => $detail->getProductName(),
                'ean13' => $detail->getEan13(),
                'sent_quantity' => $detail->getSentQuantity(),
                'recived_quantity' => $detail->getRecivedQuantity(),
                'status' => $detail->getStatus(),
                'movement_incidents' => $movementIncidentJSONComplete
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
        $newWareHouseMovement->setDateAdd(new \DateTime());
        $newWareHouseMovement->setDateModified(new \DateTime());
        $newWareHouseMovement->setIdEmployee($data['id_employee']);
        $this->entityManagerInterface->persist($newWareHouseMovement);
        $this->entityManagerInterface->flush();
        return $newWareHouseMovement;
    }

    public function generateWareHouseMovementDetail($detail, $newWareHouseMovement): LpWarehouseMovementDetails
    {
        $newWareHouseMovementDetail = new LpWarehouseMovementDetails();
        $newWareHouseMovementDetail->setSentQuantity($detail['sent_quantity']);
        $newWareHouseMovementDetail->setIdProduct($detail['id_product']);
        $newWareHouseMovementDetail->setIdProductAttribute($detail['id_product_attribute']);
        $newWareHouseMovementDetail->setProductName($detail['product_name']);
        $newWareHouseMovementDetail->setEan13($detail['ean13']);
        $newWareHouseMovementDetail->setIdWarehouseMovement($newWareHouseMovement->getIdWarehouseMovement());
        $this->entityManagerInterface->persist($newWareHouseMovementDetail);
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
            $movement->setDateModified(new \DateTime());


            if (!empty($data['movement_details'])) {
                foreach ($data['movement_details'] as $detail) {

                    $movementDetail = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class)->find($detail['id_warehouse_movement_detail']);
                    if (!$movementDetail) {
                        //Generar un nuevo movimiento detail
                        $movementDetail = new LpWarehouseMovementDetails();
                        $movementDetail->setIdWarehouseMovement($movement->getIdWarehouseMovement());
                    }
                    //Sobre escribir el movimiento detail
                    $movementDetail->setSentQuantity($detail['sent_quantity']);
                    $movementDetail->setIdProduct($detail['id_product']);
                    $movementDetail->setIdProductAttribute($detail['id_product_attribute']);
                    $movementDetail->setProductName($detail['product_name']);
                    $movementDetail->setEan13($detail['ean13']);


                    $this->entityManagerInterface->persist($movementDetail);
                    $this->entityManagerInterface->flush();
                }
            }
        }
        if ($status == "Enviado" || $status == "Recibido") {
            //Solo se puede modificar el estado y date_modified, modify_reason
            $movement->setStatus($data['status']);
            $movement->setDateModified(new \DateTime());
            $movement->setModifyReason($data['modify_reason']);
        }
        if ($status == "En revision" || $status == "Incidencia pendiente") {
            //se puede modificar el estado y movement details y date_modified, modify_reason
            $movement->setStatus($data['status']);
            $movement->setDateModified(new \DateTime());
            $movement->setModifyReason($data['modify_reason']);
            foreach ($data['movement_details'] as $detail) {

                $movementDetail = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class)->find($detail['id_warehouse_movement_detail']);

                //Sobre escribir el movimiento detail
                $movementDetail->setRecivedQuantity($detail['recived_quantity']);
                $movementDetail->setStatus($detail['status']);

                $this->entityManagerInterface->persist($movementDetail);
                $this->entityManagerInterface->flush();
            }
        }
        $this->entityManagerInterface->persist($movement);
        $this->entityManagerInterface->flush();
        return $movement;
    }
}