<?php

namespace App\Controller;

use App\Entity\PsCustomer;
use App\Entity\PsShop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController
{
    private $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/get_customers_filtered', name: 'get_customers_filtered')]
    public function getCustomers(): Response
    {
        $filter = $_GET['filter'];

        if (empty($filter)) {
            return new Response('No filter provided', Response::HTTP_BAD_REQUEST);
        }

        $customers = $this->entityManagerInterface->getRepository(PsCustomer::class)->findAllByFullNameOrId($filter);

        if (empty($customers)) {
            return new Response('No customers found', Response::HTTP_NOT_FOUND);
        }

        $customersArray = [];
        foreach ($customers as $customer) {
            $customersArray[] = [
                'id_customer' => $customer->getId(),
                'firstname' => $customer->getFirstname(),
                'lastname' => $customer->getLastname(),
                // Añade más campos que quieras incluir en la respuesta
            ];
        }

        // Convertir el array resultante a JSON
        $responseContent = json_encode($customersArray);
        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);

    }

    #[Route('/get_all_customers', name: 'get_all_customers')]
    public function getAllCustomers(): Response
    {
        // Obtener todos los clientes del repositorio
        $customers = $this->entityManagerInterface->getRepository(PsCustomer::class)->findAll();

        if (empty($customers)) {
            return new Response('No customers found', Response::HTTP_NOT_FOUND);
        }

        // Convertir los objetos a arrays simples
        $customersArray = [];
        foreach ($customers as $customer) {
            $customersArray[] = [
                'id_customer' => $customer->getId(),
                'firstname' => $customer->getFirstname(),
                'lastname' => $customer->getLastname(),
                // Añade más campos que quieras incluir en la respuesta
            ];
        }

        // Convertir el array resultante a JSON
        $responseContent = json_encode($customersArray);
        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

}