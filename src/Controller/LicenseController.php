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

    #[Route('/license_check', name: 'license_check', methods: ['GET'])]
    public function licenseCheck(Request $request): Response
    {
        $license_param = $request->query->get('license');

        if (!$license_param) {
            return new JsonResponse(['status' => 'error', 'message' => 'License parameter is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $license = $this->entityManagerInterface->getRepository(LpLicense::class)
        ->findOneByLicense($license_param);

        if (!$license) {
            return new JsonResponse(['status' => 'error', 'message' => 'License not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $currentDate = new DateTime();
        if ($license->getExpireDate()< $currentDate) {
            return new JsonResponse(['status' => 'error', 'message' => 'License expired'], JsonResponse::HTTP_FORBIDDEN);
        }

        if ($license->isActive()) {
            return new JsonResponse(['status' => 'error', 'message' => 'License already in use'], JsonResponse::HTTP_NOT_FOUND);
        }
        else {
            $license->setActive(true);
            $this->entityManagerInterface->persist($license);
            $this->entityManagerInterface->flush();
            return new JsonResponse(['status' => 'OK','message' => 'License actived']);
        }

    }
}
