<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\LpPosSessions;
use App\Entity\LpLicense;

class PosSessionController
{
    private $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/open_pos_session', name: 'open_pos_session')]
    public function openPosSession(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        // Verifica que los datos sean válidos
        if (!isset($data['id_shop'], $data['id_employee'], $data['init_balance'], $data['license'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $license_param = $data['license'];
        $pos_session = $this->entityManagerInterface->getRepository(LpPosSessions::class)
            ->findOneActiveByLicense($license_param);

        if (!$pos_session) {

            // Crea una nueva instancia de LpPosSessions
            $newPosSession = new LpPosSessions();

            // Asigna los valores recibidos a la nueva sesión
            $newPosSession->setIdShop($data['id_shop']);
            $newPosSession->setIdEmployeeOpen($data['id_employee']); // Asume que es el empleado que abre
            $newPosSession->setDateAdd(new \DateTime()); // Fecha actual
            $newPosSession->setInitBalance($data['init_balance']);
            $newPosSession->setTotalCash(0); // Puedes ajustar esto según sea necesario
            $newPosSession->setTotalCard(0); // Puedes ajustar esto según sea necesario
            $newPosSession->setTotalBizum(0); // Puedes ajustar esto según sea necesario
            $newPosSession->setActive(true); // La sesión es activa

            // Aquí debes establecer la relación con la licencia
            $license = $this->entityManagerInterface->getRepository(LpLicense::class)
                ->find($license_param);

            if ($license) {
                $newPosSession->setLicense($license); // Asumiendo que tienes un método setLicense en LpPosSessions
            } else {
                return new JsonResponse(['status' => 'error', 'message' => 'License not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            // Persistir la nueva sesión en la base de datos
            $this->entityManagerInterface->persist($newPosSession);
            $this->entityManagerInterface->flush();
            return new JsonResponse(['status' => 'OK', 'message' => 'Point Of Sale Session created']);
        } else {
            return new JsonResponse(['status' => 'KO', 'message' => 'Point Of Sale Session for today alredy created']);
        }
    }

    #[Route('/check_pos_session', name: 'check_pos_session')]
    public function checkPosSession(Request $request): Response
    {

        $license_param = $request->query->get('license');
        $pos_session = $this->entityManagerInterface->getRepository(LpPosSessions::class)
            ->findOneActiveByLicense($license_param);

        if (!$pos_session) {
            return new JsonResponse(['status' => 'KO']);
        } else {
            return new JsonResponse(['status' => 'OK']);
        }

    }

}