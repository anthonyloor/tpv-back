<?php

namespace App\Controller;

use App\Entity\PsShop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShopController extends AbstractController
{
    private $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/shops', name: 'shops_list')]
    public function getShops(): Response
    {
        $qb = $this->entityManagerInterface->createQueryBuilder();
        $qb->select('s.id_shop,s.name')
        ->from(PsShop::class,'s');
        $query = $qb->getQuery();
        $result = $query->getResult();
        return new JsonResponse($result);
    }
}