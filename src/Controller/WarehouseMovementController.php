<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\LpWarehouseMovement;
use App\Entity\LpWarehouseMovementDetails;
use App\Entity\LpWarehouseMovementIncidents;

class WarehouseMovementController extends AbstractController
{
    private $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/get_warehouse_movements', name: 'get_warehouse_movements')]
    public function getWareHouseMovements(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $repository = $this->entityManagerInterface->getRepository(LpWarehouseMovement::class);
        if (isset($data['data1'], $data['data2'])) 
            $movements = $repository->findByDateRange(new \DateTime($data['data1']), new \DateTime($data['data2']));
        else
            $movements = $repository->findBy([], ['id_warehouse_movement' => 'DESC'], 100);

            $movementIncidentJSONComplete = [];
            $movementDetailsJSONComplete = [];
            $movementsJSONComplete = [];
            foreach ($movements as $movement) {
                $movementDetails = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class)->findBy(['id_warehouse_movement' => $movement->getIdWarehouseMovement()]);

                foreach ($movementDetails as $detail) {
                    $movementIncidents = $this->entityManagerInterface->getRepository(LpWarehouseMovementIncidents::class)->findBy(['id_warehouse_movement_detail' => $detail->getIdWarehouseMovementDetail()]);
                    foreach($movementIncidents as $movementIncident){
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
                    'movement_details' => $movementDetailsJSONComplete,
                ];

            }
            return new JsonResponse($movementsJSON);
    }
}