<?php

namespace App\Controller;

use App\Entity\PsCartRule;
use App\Logic\CartRuleLogic;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CartRuleController
{
    private $entityManagerInterface;
    private CartRuleLogic $cartRuleLogic;
    public function __construct(EntityManagerInterface $entityManagerInterface, CartRuleLogic $cartRuleLogic)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->cartRuleLogic = $cartRuleLogic;
    }

    #[Route('/get_cart_rule', name: 'get_cart_rule', methods: ['GET'])]
    public function getCartRule(Request $request): Response
    {
        $code = $request->query->get('code');
        if (!$code) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $cartRule = $this->entityManagerInterface->getRepository(PsCartRule::class)->findOneBy(['code' => $code, 'active' => true]);

        if (!$cartRule) {
            return new JsonResponse(['status' => 'error', 'message' => 'Cart Rule not found'], JsonResponse::HTTP_OK);
        }

        $cartRuleData = $this->cartRuleLogic->generateCartRuleJSON($cartRule);
        return new JsonResponse($cartRuleData, JsonResponse::HTTP_OK);
    }

    #[Route('/get_cart_rules', name: 'get_cart_rules', methods: ['GET'])]
    public function getCartRules(): Response
    {
        $cartRules = $this->entityManagerInterface->getRepository(PsCartRule::class)->findBy(
            ['active' => true],
            ['id_cart_rule' => 'DESC'],
            50
        );

        $cartRulesData = array_map(function ($cartRule) {
            return $this->cartRuleLogic->generateCartRuleJSON($cartRule);
        }, $cartRules);

        return new JsonResponse($cartRulesData, JsonResponse::HTTP_OK);
    }

    #[Route('/create_cart_rule', name: 'create_cart_rule', methods: ['POST'])]
    public function createCartRule(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['date_from'], $data['date_to'], $data['description'],$data['id_customer']) && (!isset($data['reduction_amount']) || !isset($data['reduction_percent']))) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $cartRule = $this->cartRuleLogic->createCartRuleFromJSON($data);


        $cartRuleData = $this->cartRuleLogic->generateCartRuleJSON($cartRule);
        return new JsonResponse($cartRuleData, JsonResponse::HTTP_CREATED);
    }
}