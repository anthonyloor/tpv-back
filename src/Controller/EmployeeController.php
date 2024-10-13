<?php

namespace App\Controller;

use App\Entity\PsEmployee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmployeeController extends AbstractController
{
    private $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/employees', name: 'employees_list')]
    public function getEmployees(): Response
    {
        $qb = $this->entityManagerInterface->createQueryBuilder();
        $qb->select('e.id_employee','e.id_profile', "CONCAT(e.firstname, ' ', e.lastname)
            AS employee_name")
           ->from(PsEmployee::class,'e')
           ->where('e.active = 1')
           ->andWhere('e.id_profile IN (:profiles)')
           ->setParameter('profiles', [1, 6]);
        $query = $qb->getQuery();
        $result = $query->getResult();
        
        return new JsonResponse($result);
    }
}