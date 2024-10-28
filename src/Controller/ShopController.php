<?php

namespace App\Controller;

use App\Entity\PsShop;
use App\Entity\PsShopUrl;

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
        $qb->select('s.id_shop, s.name, url.virtual_uri')
            ->from(PsShop::class, 's')
            ->leftJoin(PsShopUrl::class, 'url', 'WITH', 'url.id_shop = s.id_shop')
            ->where('url.main = true')  
            ->andWhere('url.active = true');
    
        $query = $qb->getQuery();
        $result = $query->getResult();


        $result = array_map(function($shop) {
            $shop['virtual_uri'] = rtrim($shop['virtual_uri'], '/');
            return $shop;
        }, $result);

        return new JsonResponse($result);
    }
    
}