<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Redsys\RedsysService; 

class RedsysPaymetController extends AbstractController
{
    private $redsysService;

    public function __construct(RedsysService $redsysService)
    {
        // Inyecta el servicio RedsysService en el controlador
        $this->redsysService = $redsysService;
    }

    #[Route('/payment', name: 'payment', methods: ['POST'])]
    public function createPayment(Request $request)
    {
        $params = json_decode($request->getContent(), true);

        if (!isset($params['amount']) || !isset($params['order'])) {
            return new JsonResponse(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $response = $this->redsysService->createPaymentRequest($params['amount'], $params['order']);
        dump($response); // Para depuración, puedes eliminarlo después
        if ($response->getResult() == 'KO') {
            return new JsonResponse(['result' => $response->getResult()], 500);
        }

        return new JsonResponse([
            'signature' => $response['signature'],  
            'url' => "https://sis-t.redsys.es:25443/sis/realizarPago", 
            'params' => $response['params'] 
        ]);
    }
}
