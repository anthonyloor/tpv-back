<?php

namespace App\Controller;

use App\Entity\LpLicense;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class LicenseController
{
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/license_check', name: 'license_check', methods: ['POST'])]
    public function licenseCheck(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['id_shop'], $data['license'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $license_param = $data['license'];
        $id_shop = $data['id_shop'];

        $license = $this->entityManagerInterface->getRepository(LpLicense::class)
        ->findOneByLicenseAndIdShop($license_param, $id_shop);

        if (!$license) {
            return new JsonResponse(['status' => 'error', 'message' => 'License not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $currentDate = new DateTime();
        if ($license->getExpireDate()< $currentDate) {
            return new JsonResponse(['status' => 'error', 'message' => 'License expired'], JsonResponse::HTTP_FORBIDDEN);
        }

        if ($license->isActive()) {
            return new JsonResponse(['status' => 'OK', 'message' => 'License already in use']);
        }
        else {
            $license->setActive(true);
            $license->setIdShop($id_shop);
            $this->entityManagerInterface->persist($license);
            $this->entityManagerInterface->flush();
            return new JsonResponse(['status' => 'OK','message' => 'License actived']);
        }

    }
}
