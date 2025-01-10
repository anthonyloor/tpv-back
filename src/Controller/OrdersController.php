<?php

namespace App\Controller;

use App\Entity\LpPosOrders;
use App\Logic\CartRuleLogic;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\PsOrders;
use App\Entity\PsOrderDetail;
use App\Logic\OrdersLogic;
use App\Entity\LpPosSessions;
use App\Entity\PsCartRule;

class OrdersController
{
    private $entityManagerInterface;
    private OrdersLogic $ordersLogic;
    private CartRuleLogic $cartRuleLogic;

    public function __construct(EntityManagerInterface $entityManagerInterface, OrdersLogic $ordersLogic, CartRuleLogic $cartRuleLogic)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->ordersLogic = $ordersLogic;
        $this->cartRuleLogic = $cartRuleLogic;

    }

    #[Route('/create_order', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (
            !isset(
                $data['id_shop'],
                $data['id_customer'],
                $data['id_address_delivery'],
                $data['payment'],
                $data['total_cash'],
                $data['total_card'],
                $data['total_bizum'],
                $data['total_paid'],
                $data['total_paid_tax_excl'],
                $data['total_products'],
                $data['order_details'],
                $data['license'],
                $data['id_employee']
            )
        ) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $newPsOrder = $this->ordersLogic->generateOrder($data);
        $this->entityManagerInterface->persist($newPsOrder);
        $this->entityManagerInterface->flush();

        $newPosOrder = $this->ordersLogic->generatePosOrder($data, $newPsOrder);
        $this->entityManagerInterface->persist($newPosOrder);
        $this->entityManagerInterface->flush();

        $pos_session = $this->entityManagerInterface->getRepository(LpPosSessions::class)
        ->findOneActiveByLicense($data['license']);

        $total_cash = $pos_session->getTotalCash() + $data['total_cash'];
        $total_card = $pos_session->getTotalCard() + $data['total_card'];
        $total_bizum = $pos_session->getTotalBizum() + $data['total_bizum'];

        $pos_session->setTotalBizum($total_bizum);
        $pos_session->setTotalCard($total_card);
        $pos_session->setTotalCash($total_cash);
        $this->entityManagerInterface->persist($pos_session);
        $this->entityManagerInterface->flush();

        foreach ($data['order_details'] as $orderDetailData) {
            $orderDetail = $this->ordersLogic->generateOrderDetail($data, $orderDetailData, $newPsOrder);
            $this->entityManagerInterface->persist($orderDetail);
            $this->ordersLogic->updateProductStock($orderDetailData); // Llamamos a la función de actualización de stock
        }
        $this->entityManagerInterface->flush();

        if(isset($data['cart_rule'])){
            $cart_rule = $this->entityManagerInterface->getRepository(PsCartRule::class)->findOneBy(['code' => $data['cart_rule'], 'active' => true]);
            if (!$cart_rule) {
                return new JsonResponse(['status' => 'error', 'message' => 'Invalid or inactive voucher'], JsonResponse::HTTP_BAD_REQUEST);
            }
            $cart_rule->setQuantity($cart_rule->getQuantity() - 1);
            $cart_rule->setActive(false);
            $this->entityManagerInterface->persist($cart_rule);
            $this->entityManagerInterface->flush();

            $remainingAmount = $cart_rule->getReductionAmount() - $data['total_paid'];

            if($remainingAmount > 0)
            {
                $newCartRuleData = [
                    'code' => $this->cartRuleLogic->generateUniqueCartRuleCode(),
                    'description' => 'Vale descuento restante de la venta ' . $newPsOrder->getIdOrder() . ' con código ' . $data['cart_rule'],
                    'name' => 'Vale descuento restante de la venta ' . $newPsOrder->getIdOrder(),
                    'quantity' => 1,
                    'reduction_amount' => $remainingAmount,
                    'reduction_percent' => 0,
                    'active' => true,
                    'date_from' => (new \DateTime())->format('Y-m-d H:i:s'),
                    'date_to' => (new \DateTime('+1 year'))->format('Y-m-d H:i:s'),
                    'id_customer' => $data['id_customer'],
                ];

                $newCartRule = $this->cartRuleLogic->createCartRuleFromJSON($newCartRuleData);
                return new JsonResponse([
                    'status' => 'OK',
                    'message' => 'Order created with id ' . $newPsOrder->getIdOrder(),
                    'new_cart_rule_code' => $newCartRule->getCode()
                ]);
            }
        }
        return new JsonResponse(data: ['status' => 'OK', 'message' => 'Order created with id ' . $newPsOrder->getIdOrder()]);
    }

    #[Route('/get_order', name: 'get_order', methods: ['GET'])]
    public function getOrder(Request $request): Response
    {
        $id_order = $request->query->get('id_order');
        if (!$id_order) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $order = $this->entityManagerInterface->getRepository(PsOrders::class)->find($id_order);
        if (!$order) {
            return new JsonResponse(['status' => 'error', 'message' => 'Order not found'], JsonResponse::HTTP_OK);
        }
        // Construir la respuesta con la información de la orden
        $orderData = $this->ordersLogic->generateOrderJSON($order);
        // Obtener los detalles de la orden
        $orderDetails = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
            ->findBy(['idOrder' => $id_order]);

        // Procesar los detalles de la orden
        foreach ($orderDetails as $detail) {
            $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);
        }

        // Devolver la respuesta como JSON
        return new JsonResponse($orderData, JsonResponse::HTTP_OK);
    }
    #[Route('/get_orders', name: 'get_orders', methods: ['GET'])]
    public function getOrders(): Response
    {
        // Recuperar las últimas 100 órdenes
        $orders = $this->entityManagerInterface->getRepository(PsOrders::class)
            ->createQueryBuilder('o')
            ->orderBy('o.date_add', 'DESC') // Ordenar por fecha de creación descendente
            ->setMaxResults(100) // Limitar a 100 resultados
            ->getQuery()
            ->getResult();
    
        if (!$orders) {
            return new JsonResponse(['status' => 'error', 'message' => 'No orders found'], JsonResponse::HTTP_OK);
        }
    
        $responseData = [];
    
        foreach ($orders as $order) {
            $orderData = $this->ordersLogic->generateOrderJSON($order);
            // Obtener los detalles de la orden
            $orderDetails = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
                ->findBy(['idOrder' => $order->getIdOrder()]);
    
            foreach ($orderDetails as $detail) {    
                $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);
            }
            $responseData[] = $orderData;
        }
    
        // Devolver la respuesta con las órdenes como JSON
        return new JsonResponse($responseData, JsonResponse::HTTP_OK);
    }

    #[Route('/get_sale_report_orders', name: 'get_sale_report_orders', methods: ['POST'])]
    public function getSaleReportOrders(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['license'], $data['date1'], $data['date2'])) {
            return new JsonResponse(
                ['status' => 'error', 'message' => 'Invalid data provided']
            );
        }

        $posOrders = $this->entityManagerInterface->getRepository(LpPosOrders::class)
        ->getAllByLicenseAndDate($data['license'],$data['date1'], $data['date2']);
        $responseData = [];
        foreach ($posOrders as $posOrder) {
            $order = $this->entityManagerInterface->getRepository(PsOrders::class)->find($posOrder->getIdOrder());
            $orderData = $this->ordersLogic->generateSaleReportOrderJSON($order,$posOrder);
            $orderDetails = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
            ->findBy(['idOrder' => $order->getIdOrder()]);

            foreach ($orderDetails as $detail) {    
                $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);
            }
            $responseData[] = $orderData;
        }

        return new JsonResponse($responseData, JsonResponse::HTTP_OK);

    }
}
