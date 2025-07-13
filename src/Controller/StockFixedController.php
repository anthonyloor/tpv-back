<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\LpStockFixed;

class StockFixedController extends AbstractController
{
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/get_stock_fixed', name: 'get_stock_fixed')]
    public function getStockFixed(): Response
    {
        $repository = $this->entityManagerInterface->getRepository(LpStockFixed::class);
        $stocks = $repository->findAll();
        $data = [];
        foreach ($stocks as $stock) {
            $data[] = [
                'id_stock' => $stock->getIdStock(),
                'ean13' => $stock->getEan13(),
                'quantity_shop_1' => $stock->getQuantityShop1(),
                'quantity_shop_2' => $stock->getQuantityShop2(),
                'quantity_shop_3' => $stock->getQuantityShop3(),
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/create_stock_fixed', name: 'create_stock_fixed', methods: ['POST'])]
    public function createStockFixed(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['ean13'], $data['quantity_shop_1'], $data['quantity_shop_2'], $data['quantity_shop_3'])) {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $stock = new LpStockFixed();
        $stock->setEan13($data['ean13']);
        $stock->setQuantityShop1($data['quantity_shop_1']);
        $stock->setQuantityShop2($data['quantity_shop_2']);
        $stock->setQuantityShop3($data['quantity_shop_3']);
        $this->entityManagerInterface->persist($stock);
        $this->entityManagerInterface->flush();
        return new JsonResponse([
            'id_stock' => $stock->getIdStock(),
            'ean13' => $stock->getEan13(),
            'quantity_shop_1' => $stock->getQuantityShop1(),
            'quantity_shop_2' => $stock->getQuantityShop2(),
            'quantity_shop_3' => $stock->getQuantityShop3(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/update_stock_fixed', name: 'update_stock_fixed', methods: ['POST'])]
    public function updateStockFixed(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_stock'])) {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $stock = $this->entityManagerInterface->getRepository(LpStockFixed::class)->find($data['id_stock']);
        if (!$stock) {
            return new JsonResponse(['error' => 'Stock not found'], 404);
        }
        if (isset($data['quantity_shop_1'])) {
            $stock->setQuantityShop1($data['quantity_shop_1']);
        }
        if (isset($data['quantity_shop_2'])) {
            $stock->setQuantityShop2($data['quantity_shop_2']);
        }
        if (isset($data['quantity_shop_3'])) {
            $stock->setQuantityShop3($data['quantity_shop_3']);
        }
        $this->entityManagerInterface->persist($stock);
        $this->entityManagerInterface->flush();
        return new JsonResponse([
            'id_stock' => $stock->getIdStock(),
            'ean13' => $stock->getEan13(),
            'quantity_shop_1' => $stock->getQuantityShop1(),
            'quantity_shop_2' => $stock->getQuantityShop2(),
            'quantity_shop_3' => $stock->getQuantityShop3(),
        ]);
    }

    #[Route('/delete_stock_fixed', name: 'delete_stock_fixed', methods: ['POST'])]
    public function deleteStockFixed(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_stock'])) {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $stock = $this->entityManagerInterface->getRepository(LpStockFixed::class)->find($data['id_stock']);
        if (!$stock) {
            return new JsonResponse(['error' => 'Stock not found'], 404);
        }
        $this->entityManagerInterface->remove($stock);
        $this->entityManagerInterface->flush();
        return new JsonResponse(['message' => 'Stock deleted']);
    }
}
