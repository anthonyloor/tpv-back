<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PsOrderDetail;
use App\Entity\LpWarehouseMovementDetails;

class TransactionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/get_transaction_origin', name: 'get_transaction_origin', methods: ['POST'])]
    public function getTransactionOrigin(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['type'], $data['id_transaction_detail'])) {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }

        $type = $data['type'];
        $detailId = $data['id_transaction_detail'];

        switch ($type) {
            case 'order':
                $detail = $this->entityManager->getRepository(PsOrderDetail::class)
                    ->find($detailId);
                if (!$detail) {
                    return new JsonResponse(['error' => 'Order detail not found'], 404);
                }
                return new JsonResponse(['id_order' => $detail->getOrder()->getIdOrder()]);

            case 'movement':
                $detail = $this->entityManager->getRepository(LpWarehouseMovementDetails::class)
                    ->find($detailId);
                if (!$detail) {
                    return new JsonResponse(['error' => 'Movement detail not found'], 404);
                }
                return new JsonResponse(['id_warehouse_movement' => $detail->getIdWarehouseMovement()]);

            default:
                return new JsonResponse(['error' => 'Invalid type'], 400);
        }
    }
}
