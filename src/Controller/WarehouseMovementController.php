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
use App\Logic\WareHouseMovementLogic;

class WarehouseMovementController extends AbstractController
{
    private $entityManagerInterface;
    private $wareHouseMovementLogic;
    public function __construct(EntityManagerInterface $entityManagerInterface, WareHouseMovementLogic $wareHouseMovementLogic)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->wareHouseMovementLogic = $wareHouseMovementLogic;
    }

    #[Route('/get_warehouse_movements', name: 'get_warehouse_movements')]
    public function getWareHouseMovements(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $repository = $this->entityManagerInterface->getRepository(LpWarehouseMovement::class);
        if (isset($data['data1'], $data['data2']))
            $movements = $repository->findByDateRange(new \DateTime($data['data1']), new \DateTime($data['data2']));
        else
        {
            $movements = $repository->findBy([], ['id_warehouse_movement' => 'DESC'], 50);
        }
        $movementsJSONComplete = [];
        foreach($movements as $movement)
        {
            $movementsJSON = $this->wareHouseMovementLogic->generateWareHouseMovementJSON($movement);
            $movementsJSONComplete[] = $movementsJSON;
        }
        return new JsonResponse($movementsJSONComplete);
    }

    #[Route('/get_warehouse_movement', name: 'get_warehouse_movement')]
    public function getWareHouseMovement(Request $request): Response
    {
        $id = $request->query->get('id_warehouse_movement');

        $repository = $this->entityManagerInterface->getRepository(LpWarehouseMovement::class);
        $movement = $repository->find($id);
        $movementsJSON = $this->wareHouseMovementLogic->generateWareHouseMovementJSON($movement);
        $movementDetails = $this->wareHouseMovementLogic->generateWareHouseMovementDetailJSON($movement);
        $movementsJSON['movement_details'] = $movementDetails;
        return new JsonResponse($movementsJSON);
    }

    #[Route('/create_warehouse_movement', name: 'create_warehouse_movement')]
    public function createWareHouseMovement(Request $request):Response
    {
        $data = json_decode($request->getContent(), true);
        if(!isset($data['description'],$data['type'],$data['id_employee'],$data['movements_details']))
        {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $newWareHouseMovement = $this->wareHouseMovementLogic->generateWareHouseMovement($data);
        if($data['movements_details'] != null){
            foreach($data['movements_details'] as $detail)
            {
                $this->wareHouseMovementLogic->generateWareHouseMovementDetail($detail, $newWareHouseMovement);
            }
        }

        $movementsJSON = $this->wareHouseMovementLogic->generateWareHouseMovementJSON($newWareHouseMovement);

        return new JsonResponse($movementsJSON);
    }

    #[Route('/update_warehouse_movement', name: 'update_warehouse_movement')]
    public function updateWareHouseMovement(Request $request):Response
    {
        $data = json_decode($request->getContent(), true);
        if(!isset($data['id_warehouse_movement'],$data['status']))
        {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $repository = $this->entityManagerInterface->getRepository(LpWarehouseMovement::class);
        $movement = $repository->find($data['id_warehouse_movement']);
        if($movement == null)
        {
            return new JsonResponse(['error' => 'Movement not found'], 404);
        }
        $ean13_control_stock = $this->wareHouseMovementLogic->updateWareHouseMovement($data, $movement);
        $movementsJSON = $this->wareHouseMovementLogic->generateWareHouseMovementJSON($movement);
        $movementsJSON['ean13_control_stock'] = $ean13_control_stock;
        return new JsonResponse($movementsJSON);
    }

    #[Route('/execute_warehouse_movement', name: 'execute_warehouse_movement')]
    public function executeWareHouseMovement(Request $request):Response
    {
        $data = json_decode($request->getContent(), true);
        if(!isset($data['id_warehouse_movement']))
        {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $repository = $this->entityManagerInterface->getRepository(LpWarehouseMovement::class);
        $movement = $repository->find($data['id_warehouse_movement']);
        if($movement == null)
        {
            return new JsonResponse(['error' => 'Movement not found'], 404);
        }
        $this->wareHouseMovementLogic->executeWareHouseMovement($movement);
        $movementsJSON = $this->wareHouseMovementLogic->generateWareHouseMovementJSON($movement);
        return new JsonResponse($movementsJSON);
    }

    #[Route('/delete_warehouse_movement', name: 'delete_warehouse_movement')]
    public function deleteWareHouseMovement(Request $request):Response
    {
        $data = json_decode($request->getContent(), true);
        if(!isset($data['id_warehouse_movement']))
        {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $repository = $this->entityManagerInterface->getRepository(LpWarehouseMovement::class);
        $movement = $repository->find($data['id_warehouse_movement']);
        if($movement == null)
        {
            return new JsonResponse(['error' => 'Movement not found'], 404);
        }
        $detailsRepository = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class);
        $details = $detailsRepository->findBy(['id_warehouse_movement' => $movement->getIdWarehouseMovement()]);
        foreach ($details as $detail) {
            $this->entityManagerInterface->remove($detail);
            $incidentsRepository = $this->entityManagerInterface->getRepository(LpWarehouseMovementIncidents::class);
            $incidents = $incidentsRepository->findBy(['id_warehouse_movement_detail' => $detail->getIdWarehouseMovementDetail()]);
            foreach ($incidents as $incident) {
                $this->entityManagerInterface->remove($incident);
            }
        }
        $this->entityManagerInterface->remove($movement);
        $this->entityManagerInterface->flush();
        return new JsonResponse(['message' => 'Movement deleted']);
    }

    #[Route('/delete_warehouse_movement_detail', name: 'delete_warehouse_movement_detail')]
    public function deleteWareHouseMovementDetail(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_warehouse_movement_detail'])) {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $detailsRepository = $this->entityManagerInterface->getRepository(LpWarehouseMovementDetails::class);
        $detail = $detailsRepository->find($data['id_warehouse_movement_detail']);
        if ($detail == null) {
            return new JsonResponse(['error' => 'Detail not found'], 404);
        }
        $incidentsRepository = $this->entityManagerInterface->getRepository(LpWarehouseMovementIncidents::class);
        $incidents = $incidentsRepository->findBy(['id_warehouse_movement_detail' => $detail->getIdWarehouseMovementDetail()]);
        foreach ($incidents as $incident) {
            $this->entityManagerInterface->remove($incident);
        }
        $this->entityManagerInterface->remove($detail);
        $this->entityManagerInterface->flush();
        return new JsonResponse(['message' => 'Detail deleted']);
    }

}