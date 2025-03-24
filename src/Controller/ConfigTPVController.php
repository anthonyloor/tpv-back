<?php

namespace App\Controller;

use App\Entity\PsEmployee;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\LpConfigTpv;
use App\Entity\LpPin;

use App\Utils\Constants\ProfileLang;
use App\Logic\EmployeesLogic;

class ConfigTPVController
{
    private $entityManagerInterface;
    private $employeesLogic;

    public function __construct(EntityManagerInterface $entityManagerInterface, EmployeesLogic $employeesLogic)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->employeesLogic = $employeesLogic;
    }

    #[Route('/get_config_tpv', name: 'get_config_tpv')]
    public function getConfigTPV(Request $request): Response
    {
        $license = $request->query->get('license');

        if (empty($license)) {
            return new Response('No license provided', Response::HTTP_BAD_REQUEST);
        }

        $tpvConfig = $this->entityManagerInterface->getRepository(LpConfigTpv::class)
            ->findOneBy(['license' => $license]);

        if ($tpvConfig) {
            $data = [
                'license' => $tpvConfig->getLicense(),
                'id_customer_default' => $tpvConfig->getIdCustomerDefault(),
                'id_address_delivery_default' => $tpvConfig->getIdAddressDeliveryDefault(),
                'allow_out_of_stock_sales' => $tpvConfig->getAllowOutOfStockSales(),
                'ticket_text_header_1' => $tpvConfig->getTicketTextHeader1(),
                'ticket_text_header_2' => $tpvConfig->getTicketTextHeader2(),
                'ticket_text_footer_1' => $tpvConfig->getTicketTextFooter1(),
                'ticket_text_footer_2' => $tpvConfig->getTicketTextFooter2(),
            ];
            return new JsonResponse($data, Response::HTTP_OK);
        }
        return new JsonResponse(['error' => 'Configuration not found']);
    }

    #[Route('/create_config_tpv', name: 'create_config_tpv', methods: ['POST'])]
    public function createConfigTPV(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (
            !isset(
            $data['license'],
            $data['id_customer_default'],
            $data['id_address_delivery_default']
        )
        ) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $newTPVConfig = $this->entityManagerInterface->getRepository(LpConfigTpv::class)
        ->createNewTPVConfig($data);

        $this->entityManagerInterface->persist($newTPVConfig);
        $this->entityManagerInterface->flush();
        return new JsonResponse(['status' => 'success', 'message' => 'TPV Config created successfully'], JsonResponse::HTTP_CREATED);

    }

    #[Route('/update_tpv_config', name: 'update_tpv_config', methods: ['POST'])]
    public function updateTpvConfig(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Verificar que 'license' esté en los datos
        if (empty($data['license'])) {
            return new JsonResponse(['error' => 'No license provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Actualizar la configuración TPV usando el repositorio
        $updatedTPVConfig = $this->entityManagerInterface->getRepository(LpConfigTpv::class)
        ->updateTPVConfig($data);
        if (!$updatedTPVConfig) {
            
            return new JsonResponse(['error' => 'Configuration not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Persistir y guardar los cambios en la base de datos
        $this->entityManagerInterface->persist($updatedTPVConfig);
        $this->entityManagerInterface->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'TPV Config updated successfully'], JsonResponse::HTTP_OK);
    }

    #[Route('/get_pin', name: 'get_pin', methods: ['GET'])]
    public function getPin(Request $request):Response
    {
        $data = json_decode($request->getContent(), true);
        if(!isset($data['id_employee_request']))
        {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $repository = $this->entityManagerInterface->getRepository(LpPin::class);
        $pin = $repository->findOneBy(['active' => true]);
        if($pin == null)
        {
            return new JsonResponse(['error' => 'Pin not found'], 404);
        }

        $employeeRepository = $this->entityManagerInterface->getRepository(PsEmployee::class);
        $employee = $employeeRepository->find($data['id_employee_request']);

        if (!$employee) {
            return new JsonResponse(['error' => 'Employee not found'], 404);
        }

        if(!$this->employeesLogic->checkUserRol(ProfileLang::SUPER_ADMIN, $employee))
        {
            return new JsonResponse(['error' => 'Non administrators cannot request pins'], 400);
        }

        $pin->setIdEmployeeRequest($data['id_employee_request']);
        $this->entityManagerInterface->persist($pin);
        $this->entityManagerInterface->flush();

        $pinJSON = [
            'id_pin' => $pin->getIdPin(),
            'pin' => $pin->getPin(),
            'date_add' => $pin->getDateAdd()->format('Y-m-d H:i:s'),
            'active' => $pin->isActive(),
        ];
        return new JsonResponse($pinJSON);
    }

    #[Route('/check_pin', name: 'check_pin', methods: ['GET'])]
    public function checkPin(Request $request):Response
    {
        $data = json_decode($request->getContent(), true);
        if(!isset($data['pin'], $data['id_employee_used'], $data['reason']))
        {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }
        $repository = $this->entityManagerInterface->getRepository(LpPin::class);
        $pin = $repository->findOneBy(['pin' => $data['pin'], 'active' => true]);
        if($pin == null)
        {
            return new JsonResponse(['error' => 'Pin not found or already used'], 404);
        }

        $pinJSON = [
            'usable' => true
        ];

        $pin->setActive(false);
        $pin->setIdEmployeeUsed($data['id_employee_used']);
        $pin->setReason($data['reason']);
        $pin->setDateUsed(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $this->entityManagerInterface->persist($pin);

        $newPin = new LpPin();
        do {
            $randomPin = mt_rand(1000, 9999); // Generate a random 6-digit pin
            $existingPin = $this->entityManagerInterface->getRepository(LpPin::class)
            ->findOneBy(['pin' => $randomPin]);
        } while ($existingPin !== null);

        $newPin->setPin($randomPin);
        $newPin->setActive(true);
        $newPin->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $this->entityManagerInterface->persist($newPin);

        $this->entityManagerInterface->flush();



        return new JsonResponse($pinJSON);
    }
}
