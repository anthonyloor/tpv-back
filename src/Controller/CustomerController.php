<?php

namespace App\Controller;

use App\Entity\PsCustomer;
use App\EntityFajasMaylu\PsCustomer as PsCustomerMaylu;
use App\EntityFajasMaylu\PsAddress as PsAddressMaylu;
use App\Entity\PsAddress;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PsGroup;
use App\Entity\PsGroupLang;
use App\Logic\CustomerLogic;


class CustomerController
{
    private $entityManagerInterface;
    private $emFajasMaylu;
    private $customerLogic;
    public function __construct(ManagerRegistry $doctrine, CustomerLogic $customerLogic)
    {
        $this->entityManagerInterface = $doctrine->getManager('default');
        $this->emFajasMaylu = $doctrine->getManager('fajas_maylu');
        $this->customerLogic = $customerLogic;
    }

    #[Route('/get_customers_filtered', name: 'get_customers_filtered')]
    public function getCustomers(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Verifica que al menos exista 'origin'
        if (!isset($data['origin'])) {
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
        }

        $customers = [];

        // Si viene id_customer, se busca por ID
        if (isset($data['id_customer'])) {
            switch ($data['origin']) {
                case 'fajasmaylu':
                    // Ajusta el método si en PsCustomerMaylu también creas un findAllByFullNameOrId
                    $customers = $this->emFajasMaylu
                        ->getRepository(PsCustomerMaylu::class)
                        ->findByCustomerById($data['id_customer']);
                    break;
                case 'mayret':
                    $customers = $this->entityManagerInterface
                        ->getRepository(PsCustomer::class)
                        ->findByCustomerById($data['id_customer']);
                    break;
                case 'all':
                    $customersMayret = $this->entityManagerInterface
                        ->getRepository(PsCustomer::class)
                        ->findByCustomerById($data['id_customer']);
                    // De igual forma, usaría el método en la entidad PsCustomerMaylu
                    $customersMaylu = $this->emFajasMaylu
                        ->getRepository(PsCustomerMaylu::class)
                        ->findByCustomerById($data['id_customer']);
                    $customers = array_merge($customersMayret, $customersMaylu);
                    break;
                default:
                    $customers = $this->entityManagerInterface
                        ->getRepository(PsCustomer::class)
                        ->findByCustomerById($data['id_customer']);
                    break;
            }
            // Si no hay id_customer, entonces revisa si viene filter y busca por filtro
        } elseif (isset($data['filter'])) {
            switch ($data['origin']) {
                case 'fajasmaylu':
                    $customers = $this->emFajasMaylu
                        ->getRepository(PsCustomerMaylu::class)
                        ->findAllByFullNameOrPhone($data['filter']);
                    break;
                case 'mayret':
                    $customers = $this->entityManagerInterface
                        ->getRepository(PsCustomer::class)
                        ->findAllByFullNameOrPhone($data['filter']);
                    break;
                case 'all':
                    $customersMayret = $this->entityManagerInterface
                        ->getRepository(PsCustomer::class)
                        ->findAllByFullNameOrPhone($data['filter']);
                    $customersMaylu = $this->emFajasMaylu
                        ->getRepository(PsCustomerMaylu::class)
                        ->findAllByFullNameOrPhone($data['filter']);
                    $customers = array_merge($customersMayret, $customersMaylu);
                    break;
                default:
                    $customers = $this->entityManagerInterface
                        ->getRepository(PsCustomer::class)
                        ->findAllByFullNameOrPhone($data['filter']);
                    break;
            }
        } else {
            // Si no viene ni id_customer ni filter
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
        }

        if (empty($customers)) {
            return new Response('No customers found', Response::HTTP_NOT_FOUND);
        }

        // Genera el array en formato deseado
        $customersArray = $this->customerLogic->generateJSONCustomer($customers);
        $responseContent = json_encode($customersArray);

        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/get_all_customers', name: 'get_all_customers')]
    public function getAllCustomers(): Response
    {
        $customers = $this->entityManagerInterface->getRepository(PsCustomer::class)->findBy([], ['id_customer' => 'DESC'], 25);
        $customersMaylu = $this->emFajasMaylu->getRepository(PsCustomerMaylu::class)->findBy([], ['id_customer' => 'DESC'], 25);


        if (empty($customers)) {
            return new Response('No customers found', Response::HTTP_NOT_FOUND);
        }
        // Merge both customer arrays
        $allCustomers = array_merge($customers, $customersMaylu);

        $customersArray = $this->customerLogic->generateJSONCustomer($allCustomers);

        $responseContent = json_encode($customersArray);
        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    #[Route('/get_addresses', name: 'get_addresses')]
    public function getAddressesByCustomer(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['id_customer'], $data['origin'])) {
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
        }

        switch ($data['origin']) {
            case 'fajasmaylu':
                $addresses = $this->emFajasMaylu->getRepository(PsAddressMaylu::class)->findAllByCustomerId1($data['id_customer']);
                break;
            case 'mayret':
                $addresses = $this->entityManagerInterface->getRepository(PsAddress::class)->findAllByCustomerId($data['id_customer']);
                break;
            case 'all':
                $addressesMayret = $this->entityManagerInterface->getRepository(PsAddress::class)->findAllByCustomerId($data['id_customer']);
                $addressesMaylu = $this->emFajasMaylu->getRepository(PsAddressMaylu::class)->findAllByCustomerId1($data['id_customer']);
                $addresses = array_merge($addressesMayret, $addressesMaylu);
                break;
            default:
                $addresses = $this->entityManagerInterface->getRepository(PsAddress::class)->findAllByCustomerId($data['id_customer']);
                break;
        }
        if (empty($addresses)) {
            return new Response('No addresses found for this customer.', Response::HTTP_NOT_FOUND);
        }

        $addressesArray = $this->customerLogic->generateJSONAdresses($addresses);
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

    #[Route('/edit_customer', name: 'edit_customer', methods: ['POST'])]
    public function editCustomer(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['id_customer']) || empty($data['firstname']) || empty($data['lastname'])) {
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
        }

        $customer = $this->entityManagerInterface->getRepository(PsCustomer::class)->find($data['id_customer']);
        if (empty($customer)) {
            return new Response('Customer not found', Response::HTTP_NOT_FOUND);
        }

        $customer->setFirstname($data['firstname']);
        $customer->setLastname($data['lastname']);
        $this->entityManagerInterface->flush();

        return new JsonResponse(['message' => 'Customer updated successfully'], Response::HTTP_OK);
    }

    #[Route('/edit_address', name: 'edit_address', methods: ['POST'])]
    public function editAdress(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['id_address']) || empty($data['id_customer']) || empty($data['id_country']) || empty($data['id_state']) || empty($data['alias']) || empty($data['lastname']) || empty($data['firstname']) || empty($data['address1']) || empty($data['postcode']) || empty($data['city'])) {
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
        }

        $address = $this->entityManagerInterface->getRepository(PsAddress::class)->find($data['id_address']);
        if (empty($address)) {
            return new Response('Address not found', Response::HTTP_NOT_FOUND);
        }

        $address->setIdCountry($data['id_country']);
        $address->setIdState($data['id_state']);
        $address->setAlias($data['alias']);
        $address->setCompany($data['company']);
        $address->setLastname($data['lastname']);
        $address->setFirstname($data['firstname']);
        $address->setAddress1($data['address1']);
        $address->setAddress2($data['address2']);
        $address->setPostcode($data['postcode']);
        $address->setCity($data['city']);
        $address->setOther($data['other']);
        $address->setPhone($data['phone']);
        $address->setPhoneMobile($data['phone_mobile']);
        $address->setVatNumber($data['vat_number']);
        $address->setDni($data['dni']);
        $address->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $this->entityManagerInterface->flush();

        return new JsonResponse(['message' => 'Address updated successfully'], Response::HTTP_OK);
    }

}