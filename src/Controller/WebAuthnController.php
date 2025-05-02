<?php

namespace App\Controller;

use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\AttestationConveyancePreference;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
 

use App\Entity\PsEmployee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WebAuthnController
{

    private $entityManager;
    private $session;
    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    #[Route('/webauthn/registration-options', methods: ['GET'])]
    public function registrationOptions(Request $request): JsonResponse
    {
        //$idEmployee = $request->query->get('id_employee');
        $idEmployee = 25; // Para el ejemplo, puedes cambiarlo por el ID real del empleado
        
        // ⚠️ Aquí deberías buscar al empleado en la base de datos
        // Simulación para el ejemplo:

        $employee = $this->entityManager->getRepository(PsEmployee::class)->find($idEmployee);

        $user = new PublicKeyCredentialUserEntity(
            $idEmployee, // id debe ser un string único por usuario
            'Empleado '.$idEmployee,
            "" // icono opcional
        );

        $rp = new PublicKeyCredentialRpEntity('TPV Mayret', 'localhost');

        $challenge = random_bytes(32);

        $pubKeyCredParams = [
            new PublicKeyCredentialParameters('public-key', -7),
            new PublicKeyCredentialParameters('public-key', -257)
        ];

        // $authSelection = new AuthenticatorSelectionCriteria(
        //     null,
        //     false,
        //     'discouraged'
        // );
        $options = new PublicKeyCredentialCreationOptions(
            $rp,
            $user,
            $challenge,
            $pubKeyCredParams,
            null, // Exclude credentials (si es necesario)
            'none',
            [],
            60000
        );

        // ⚠️ Guarda temporalmente el objeto en sesión para después validarlo
        $request->getSession()->set('register_options', $options);

        return new JsonResponse([
            'challenge' => base64_encode($challenge),
            'rp' => [
                'name' => 'TPV Mayret',
                'id' => 'localhost',
            ],
            'user' => [
                'id' => base64_encode($idEmployee),  // asegúrate de codificar a base64
                'name' => 'Empleado '.$idEmployee,
                'displayName' => 'Empleado '.$idEmployee,
            ],
            'pubKeyCredParams' => [
                ['type' => 'public-key', 'alg' => -7],
                ['type' => 'public-key', 'alg' => -257],
            ],
            'authenticatorSelection' => [
                'userVerification' => 'preferred',
                'requireResidentKey' => false,
            ],
            'timeout' => 60000,
            'attestation' => 'none'
        ]);    
    }


}