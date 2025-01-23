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

    public function generateWareHouseMovementJSON($movements):array
    {
        $movementIncidentJSONComplete = [];
        $movementDetailsJSONComplete = [];
        foreach ($movements as $movement) {
            $movementDetails = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class)->findBy(['id_warehouse_movement' => $movement->getIdWarehouseMovement()]);

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
                    'sent_quantity' => $detail->getSentQuantity(),
                    'recived_quantity' => $detail->getRecivedQuantity(),
                    'movement_incidents' => $movementIncidentJSONComplete
                ];
                $movementDetailsJSONComplete[] = $movementDetailsJSON;
            }
            $movementsJSON = [
                'id_warehouse_movement' => $movement->getIdWarehouseMovement(),
                'date_add' => $movement->getDateAdd()->format('Y-m-d'),
                'description' => $movement->getDescription(),
                'id_shop_origin' => $movement->getIdShopOrigin(),
                'id_shop_destiny' => $movement->getIdShopDestiny(),
                'status' => $movement->getStatus(),
                'type' => $movement->getType(),
                'movement_details' => $movementDetailsJSONComplete
            ];
        }
        return $movementsJSON;

    }

    public function generateWareHouseMovement($data):LpWarehouseMovement
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

    public function generateWareHouseMovementDetail($detail,$newWareHouseMovement):LpWarehouseMovementDetails
    {
        $newWareHouseMovementDetail = new LpWarehouseMovementDetails();
        $newWareHouseMovementDetail->setSentQuantity($detail['sent_quantity']);
        $newWareHouseMovementDetail->setIdProduct($detail['id_product']);
        $newWareHouseMovementDetail->setIdProductAttribute($detail['id_product_attribute']);
        $newWareHouseMovementDetail->setProductName($detail['product_name']);
        $newWareHouseMovementDetail->setIdWarehouseMovement($newWareHouseMovement->getIdWarehouseMovement());
        $this->entityManagerInterface->persist($newWareHouseMovementDetail);
        $this->entityManagerInterface->flush();
        return $newWareHouseMovementDetail;
    }
}