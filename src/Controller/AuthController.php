<?php

namespace App\Controller;

use App\Entity\PsEmployee;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Doctrine\ORM\EntityManagerInterface; // Asegúrate de que esta clase está importada

class AuthController extends AbstractController
{
    private $jwtManager;
    private $passwordHasher;
    private $entityManagerInterface;

    public function __construct(JWTTokenManagerInterface $jwtManager, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManagerInterface)
    {
        $this->jwtManager = $jwtManager;
        $this->passwordHasher = $passwordHasher;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['id_employee']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Invalid input'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Cambiar 'email' a 'passwd' si es necesario
        $user = $this->entityManagerInterface->getRepository(PsEmployee::class)->findOneBy(['id_employee' => $data['id_employee']]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            throw new AuthenticationException(message: 'Invalid credentials.');
        }

        // Generar el token JWT
        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}