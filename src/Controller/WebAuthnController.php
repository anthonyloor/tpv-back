<?php

namespace App\Controller;

use Webauthn\AttestationStatement\AttestationObject;
use Webauthn\AttestationStatement\AttestationStatement;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\CeremonyStep\CeremonyStepManager;
use Webauthn\CollectedClientData;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\PublicKeyCredentialParameters;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
 

use App\Entity\PsEmployee;
use App\Entity\LpWebAuthnCredential;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\Util\Base64;

class WebAuthnController
{

    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->session = $requestStack->getSession();
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

    #[Route('/webauthn/register', methods: ['POST'])]
    public function finishRegistration(Request $request): JsonResponse
    {
        $session = $this->session;
        $data = json_decode($request->getContent(), true);
        $credential = $data['credential'] ?? null;
    
        if (!$credential) {
            return new JsonResponse(['error' => 'Credencial no enviada'], 400);
        }
    
        $serializedOptions = $session->get('webauthn_creation_options');
        $employeeId = $session->get('webauthn_employee_id');
    
        if (!$serializedOptions || !$employeeId) {
            return new JsonResponse(['error' => 'Sesión inválida o expirada'], 400);
        }
    
        /** @var PublicKeyCredentialCreationOptions $creationOptions */
        $creationOptions = unserialize($serializedOptions);
    
        try {
            // Decodificar datos base64url del navegador
            $rawId = Base64::decode($credential['rawId']);
            $attestationObject = Base64::decode($credential['response']['attestationObject']);
            $clientDataJSON = Base64::decode($credential['response']['clientDataJSON']);

            $clientData = new CollectedClientData(
                $credential['response']['clientDataJSON'],
                $credential['response']['clientDataJSON']['type']
            );

            $attstm = new AttestationStatement(
                $credential['response']['attestationObject'],
                $credential['response']['attestationObject']['fmt'],
                $credential['response']['attestationObject']['authData'],
                $credential['response']['attestationObject']['attStmt']
            );

            $attestationObject = new AttestationObject(
                $credential['response']['attestationObject'],
                $attstm,
                $credential['response']['attestationObject']['authData']
            );
    
            // Crear objetos de la respuesta del cliente
            $attestationResponse = new AuthenticatorAttestationResponse(
                $clientData,
                $attestationObject
            );

            // Validar credencial usando la librería
            $ceremony = new CeremonyStepManager(
                [new \Webauthn\AttestationStatement\NoneAttestationStatementSupport()]
            );
            $validator = new AuthenticatorAttestationResponseValidator($ceremony);
    
            $publicKeyCredentialSource = $validator->check(
                $attestationResponse,
                $creationOptions,
                $rawId
            );
    
            // // Guardar credencial en base de datos
            // $credentialEntity = new LpWebAuthnCredential();
            // $credentialEntity->setCredentialId($publicKeyCredentialSource->getPublicKeyCredentialDescriptor()->getId());
            // $credentialEntity->setPublicKey($publicKeyCredentialSource->getCredentialPublicKey());
            // $credentialEntity->setUserHandle($publicKeyCredentialSource->getUserHandle());
            // $credentialEntity->setUser($this->entityManager->getRepository(PsEmployee::class)->find($employeeId));
    
            // $this->entityManager->persist($credentialEntity);
            // $this->entityManager->flush();
    
            return new JsonResponse(['success' => true]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Error en el registro: ' . $e->getMessage()], 500);
        }
    }

}