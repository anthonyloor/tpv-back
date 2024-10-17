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

        $qb = $this->entityManagerInterface->createQueryBuilder();
        $qb->select('l.license', 'l.expire_date', 'l.active')
           ->from(LpLicense::class, 'l')
           ->where('l.active = 1')
           ->andWhere('l.license = :license')
           ->setParameter('license', $license_param);

        $result = $qb->getQuery()->getOneOrNullResult();

        if (!$result) {
            return new JsonResponse(['status' => 'error', 'message' => 'License not found or inactive'], JsonResponse::HTTP_NOT_FOUND);
        }

        $currentDate = new DateTime();
        if ($result['expire_date'] < $currentDate) {
            return new JsonResponse(['status' => 'error', 'message' => 'License expired'], JsonResponse::HTTP_FORBIDDEN);
        }

        return new JsonResponse(['status' => 'OK']);
    }
}
