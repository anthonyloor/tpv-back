<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class RedsysPaymetController extends AbstractController
{


    #[Route('/payment', name: 'payment', methods: ['POST'])]
    public function createPayment(Request $request): JsonResponse
    {


    }
}
