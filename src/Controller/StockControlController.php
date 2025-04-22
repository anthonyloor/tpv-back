<?php

namespace App\Controller;

use App\Entity\LpControlStock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Logic\StockControllLogic;

class StockControlController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private $stockControllLogic;
    public function __construct(EntityManagerInterface $entityManager, StockControllLogic $stockControllLogic)
    {
        $this->entityManager = $entityManager;
        $this->stockControllLogic = $stockControllLogic;
    }

    #[Route('/get_controll_stocks', name: 'get_controll_stocks')]
    public function getControllStocks(): Response
    {
        $controll_stocks = $this->entityManager->getRepository(LpControlStock::class)->findBy([], ['id_control_stock' => 'DESC'], 200);

        $controll_stocksJSONComplete = [];
        foreach ($controll_stocks as $controll_stock) {
            $controll_stockJSON = $this->stockControllLogic->generateControlStockJSONComplete($controll_stock);
            $controll_stocksJSONComplete[] = $controll_stockJSON;
        }
        return new JsonResponse($controll_stocksJSONComplete);
    }

    #[Route('/get_controll_stock', name: 'get_controll_stock')]
    public function getControllStock(Request $request): Response
    {
        $id = $request->query->get('id');
        $controll_stock = $this->entityManager->getRepository(LpControlStock::class)->findOneBy(['id_control_stock' => $id]);
        if (!$controll_stock){
            return new JsonResponse(['error' => 'Control stock not found'], Response::HTTP_NOT_FOUND);
        }
        $controll_stockJSON = $this->stockControllLogic->generateControlStockJSONComplete($controll_stock);
        $controll_stockJSON['history'] = $this->stockControllLogic->generateControlStockHistoryJSON($controll_stock);
        return new JsonResponse($controll_stockJSON);

    }
}