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
        if (!isset($data['id_shop'], $data['id_employee'], $data['init_cash'], $data['license'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $pos_session = $this->getActiveSessionByLicense($data['license']);

        if (!$pos_session) {

            $newPosSession = new LpPosSessions();

            $newPosSession->setIdShop($data['id_shop']);
            $newPosSession->setIdEmployeeOpen($data['id_employee']); 
            $newPosSession->setDateAdd(new \DateTime()); // Fecha actual
            $newPosSession->setInitCash($data['init_cash']);
            $newPosSession->setActive(true); 

            $license = $this->entityManagerInterface->getRepository(LpLicense::class)
                ->find($data['license']);

            if ($license) {
                $newPosSession->setLicense($license); // Asumiendo que tienes un método setLicense en LpPosSessions
            } else {
                return new JsonResponse(['status' => 'error', 'message' => 'License not found'], JsonResponse::HTTP_NOT_FOUND);
            }

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
        $pos_session = $this->getActiveSessionByLicense($license_param);

        if (!$pos_session) {
            return new JsonResponse(['status' => 'KO']);
        } else {
            return new JsonResponse(['status' => 'OK']);
        }

    }

    #[Route('/close_pos_session', name: 'close_pos_session')]
    public function closePosSession(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        // Verifica que los datos sean válidos
        if (!isset($data['license'],$data['id_employee'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $pos_session = $this->getActiveSessionByLicense($data['license']);

        if ($pos_session) {            
            $pos_session->setActive(false); // Desactivamos la sesion
            $pos_session->setIdEmployeeClose($data['id_employee']);
            $pos_session->setDateClose(new \DateTime());
            $this->entityManagerInterface->persist($pos_session);
            $this->entityManagerInterface->flush();
            return new JsonResponse(['status' => 'OK', 'message' => 'Point Of Sale Session updated']);
        } else {
            return new JsonResponse(['status' => 'KO', 'message' => 'Point Of Sale Session not found']);
        }
    }

    #[Route('/get_report_amounts', name: 'get_report_amounts')]
    public function getReportAmounts(Request $request): Response
    {
        $license_param = $request->query->get('license');
        $pos_session = $this->getActiveSessionByLicense($license_param);

        if ($pos_session) {
            return new JsonResponse([
                'status' => 'OK',
                'total_cash' => $pos_session->getTotalCash(),
                'total_card' => $pos_session->getTotalCard(),
                'total_bizum' => $pos_session->getTotalBizum()
            ]);
        } else {
            return new JsonResponse(['status' => 'KO', 'message' => 'Point Of Sale Session not found']);
        }
    }

    private function getActiveSessionByLicense($license_param)
    {
        return $this->entityManagerInterface->getRepository(LpPosSessions::class)
            ->findOneActiveByLicense($license_param);
    }


}