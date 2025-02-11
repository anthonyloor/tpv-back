<?php

namespace App\Controller;

use App\Entity\PsCustomer;
use App\Entity\PsAddress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PsGroup;
use App\Entity\PsGroupLang;
use App\Logic\CustomerLogic;


class CustomerController
{
    private $entityManagerInterface;
    private $customerLogic;
    public function __construct(EntityManagerInterface $entityManagerInterface, CustomerLogic $customerLogic)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->customerLogic = $customerLogic;
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
            ];
        }

        // Convertir el array resultante a JSON
        $responseContent = json_encode($customersArray);
        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);

    }

    #[Route('/get_all_customers', name: 'get_all_customers')]
    public function getAllCustomers(): Response
    {
		$customers = $this->entityManagerInterface->getRepository(PsCustomer::class)->findBy([], ['id_customer' => 'DESC'], 25);
        
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
            ];
        }

        $responseContent = json_encode($customersArray);
        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    #[Route('/get_addresses', name: 'get_addresses')]
    public function getAddressesByCustomer(): Response
    {
        $customerId = $_GET['customer'];

        $addresses = $this->entityManagerInterface->getRepository(PsAddress::class)->findAllByCustomerId($customerId);

        if (empty($addresses)) {
            return new Response('No addresses found for this customer.', Response::HTTP_NOT_FOUND);
        }

        $addressesArray = [];
        foreach ($addresses as $address) {
            $addressesArray[] = [
                'id_address' => $address->getId(),
                'id_country' => $address->getIdCountry(),
                'id_state' => $address->getIdState(),
                'id_customer' => $address->getIdCustomer(),
                'alias' => $address->getAlias(),
                'company' => $address->getCompany(),
                'lastname' => $address->getLastname(),
                'firstname' => $address->getFirstname(),
                'address1' => $address->getAddress1(),
                'address2' => $address->getAddress2(),
                'postcode' => $address->getPostcode(),
                'city' => $address->getCity(),
                'other' => $address->getOther(),
                'phone' => $address->getPhone(),
                'phone_mobile' => $address->getPhoneMobile(),
                'vat_number' => $address->getVatNumber(),
                'dni' => $address->getDni(),
                'date_add' => $address->getDateAdd()->format('Y-m-d H:i:s'),
                'date_upd' => $address->getDateUpd()->format('Y-m-d H:i:s'),
                'active' => $address->isActive(),
                'deleted' => $address->isDeleted(),
            ];
        }

        $responseContent = json_encode($addressesArray);
        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/get_groups', name: 'get_groups')]
    public function getGroups(): Response
    {
        $groups = $this->entityManagerInterface->getRepository(PsGroup::class)->findAll();

        if (empty($groups)) {
            return new Response('No groups found', Response::HTTP_NOT_FOUND);
        }


        $groupsArray = [];
        foreach ($groups as $group) {
            $groupLang = $this->entityManagerInterface->getRepository(PsGroupLang::class)->findOneBy(['id_group' => $group->getIdGroup()]);
            $groupsArray[] = [
                'id_group' => $group->getIdGroup(),
                'name' => $groupLang->getName(), 
            ];
        }

        $responseContent = json_encode($groupsArray);
        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/create_customer', name: 'create_customer', methods: ['POST'])]
    public function createCustomer(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['firstname']) || empty($data['lastname'])) {
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
        }
        
        $customer = $this->customerLogic->createCustomer($data);
        $this->entityManagerInterface->persist($customer);
        $this->entityManagerInterface->flush();

        return new JsonResponse(['message' => 'Customer created successfully', 'id_customer' => $customer->getId()], Response::HTTP_CREATED);
    }

    #[Route('/create_address', name: 'create_address', methods: ['POST'])]
    public function createAddress(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['id_customer']) || empty($data['id_country']) || empty($data['id_state']) || empty($data['alias']) || empty($data['lastname']) || empty($data['firstname']) || empty($data['address1']) || empty($data['postcode']) || empty($data['city'])) {
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
        }
        

        $address = $this->customerLogic->createAddres($data);
        $this->entityManagerInterface->persist($address);
        $this->entityManagerInterface->flush();

        return new JsonResponse(['message' => 'Address created successfully', 'id_address' => $address->getId()], Response::HTTP_CREATED);
    }
}