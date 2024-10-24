<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\PsOrders;
use App\Entity\PsOrderDetail;

class OrdersController
{
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/crete_order', name: 'crete_order', methods: ['POST'])]
    public function createOrder(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_shop'], $data['id_customer'],$data['id_address_delivery'])) 
        {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}