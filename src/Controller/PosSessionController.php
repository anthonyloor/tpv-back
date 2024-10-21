<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\LpPosSessions;

class PosSessionController
{
    private $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    // #[Route('/open_pos_session', name: 'open_pos_session')]
    // public function openPosSession(): Response
    // {
    //     $qb = $this->entityManagerInterface->createQueryBuilder();
    //     $qb->select('p.id_pos_sessions')
    //     ->from(LpPosSessions::class,'p')
    //     ->where('p.active = 1')
    //     ->andWhere('p.id_shop = :profiles')
    //     ->andWhere('p.id_employee_open = :profiles')

    //     return new JsonResponse(['status' => 'OK']);
    // }
}