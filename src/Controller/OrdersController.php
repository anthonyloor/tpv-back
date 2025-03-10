<?php

namespace App\Controller;

use App\Entity\LpPosOrders;
use App\EntityFajasMaylu\PsOrders as PsOrdersFajasMaylu;
use App\Logic\CartRuleLogic;
use App\Logic\StockControllLogic;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\PsOrders;
use App\Entity\PsOrderDetail;
use App\Logic\OrdersLogic;
use App\Entity\LpPosSessions;
use App\Entity\PsCartRule;
use App\Entity\PsCartRuleLang;
use App\Entity\PsOrderCartRule;
use App\Entity\LpControlStock;

class OrdersController
{
    private $entityManagerInterface;
    private $emFajasMaylu;
    private OrdersLogic $ordersLogic;
    private CartRuleLogic $cartRuleLogic;

    private StockControllLogic $stockControllLogic;

    public function __construct(ManagerRegistry $doctrine, OrdersLogic $ordersLogic, CartRuleLogic $cartRuleLogic, StockControllLogic $stockControllLogic)
    {
        $this->entityManagerInterface = $doctrine->getManager('default');
        $this->emFajasMaylu = $doctrine->getManager('fajas_maylu');
        $this->ordersLogic = $ordersLogic;
        $this->cartRuleLogic = $cartRuleLogic;
        $this->stockControllLogic = $stockControllLogic;

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
            $data['id_employee'],
            $data['total_discounts'],
            $data['total_discounts_tax_excl']
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
        if(isset($orderDetailData['id_control_stock']))
        {
            $controlStock = $this->entityManagerInterface->getRepository(LpControlStock::class)->find($orderDetailData['id_control_stock']);
            if ($orderDetailData['product_quantity'] > 0) {
                $this->stockControllLogic->createControlStockHistory($orderDetailData['id_control_stock'], 'Venta de producto', 'Venta', $data['id_shop']);
                $controlStock->setActive(active: false);
            } else {
                $this->stockControllLogic->createControlStockHistory($orderDetailData['id_control_stock'], 'Devolución de producto', 'Devolución', $data['id_shop']);
                $controlStock->setActive(active: true);
            }
            $controlStock->setDateUpd(new \DateTime());
            $this->entityManagerInterface->persist($controlStock);
        }
        $this->entityManagerInterface->flush();

        if (isset($data['discounts'])) {
            $newCartRule = null;
            foreach ($data['discounts'] as $discount) {
                $cart_rule = $this->entityManagerInterface->getRepository(PsCartRule::class)->findOneBy(['code' => $discount['code'], 'active' => true]);
                if (!$cart_rule) {
                    return new JsonResponse(['status' => 'error', 'message' => 'Invalid or inactive voucher'], JsonResponse::HTTP_BAD_REQUEST);
                }
                $cart_rule->setQuantity($cart_rule->getQuantity() - 1);
                $cart_rule->setActive(false);
                $this->entityManagerInterface->persist($cart_rule);
                $this->entityManagerInterface->flush();


                $orderCartRule = $this->cartRuleLogic->generateOrderCartRule($newPsOrder, $cart_rule, $discount);
                $this->entityManagerInterface->persist($orderCartRule);

                $this->entityManagerInterface->flush();

                $remainingAmount = $cart_rule->getReductionAmount() - $discount['amount'];

                if ($remainingAmount > 0) {
                    $newCartRuleData = [
                        'code' => $this->cartRuleLogic->generateUniqueCartRuleCode(),
                        'description' => 'Vale descuento restante de la venta ' . $newPsOrder->getIdOrder() . ' con vale descuento ' . $discount['code'],
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
                }
            }
            $response = [
                'status' => 'OK',
                'message' => 'Order created with id ' . $newPsOrder->getIdOrder()
            ];

            if ($newCartRule) {
                $response['new_cart_rule_code'] = $newCartRule->getCode();
            }

            return new JsonResponse($response);
        }
        return new JsonResponse(data: ['status' => 'OK', 'message' => 'Order created with id ' . $newPsOrder->getIdOrder()]);
    }

    #[Route('/get_order', name: 'get_order', methods: ['GET'])]
    public function getOrderByIdAndOrigin(Request $request): Response
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

        // Obtener los cart rules de la orden
        $orderCartRules = $this->entityManagerInterface->getRepository(PsOrderCartRule::class)
            ->findBy(['id_order' => $id_order]);

        // Procesar los cart rules de la orden
        foreach ($orderCartRules as $orderCartRule) {
            $cartRule = $this->entityManagerInterface->getRepository(PsCartRule::class)
                ->find($orderCartRule->getIdCartRule());
            if ($cartRule) {
                $cartRuleLang = $this->entityManagerInterface->getRepository(PsCartRuleLang::class)
                    ->findOneBy(['id_cart_rule' => $cartRule->getIdCartRule()]);
                $orderData['order_cart_rules'][] = [
                    'code' => $cartRule->getCode(),
                    'name' => $cartRuleLang ? $cartRuleLang->getName() : $orderCartRule->getName(),
                    'value' => $orderCartRule->getValue()
                ];
            }
        }
        // Obtener los detalles de la orden
        $orderDetails = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
            ->findBy(['idOrder' => $id_order]);

        // Procesar los detalles de la orden
        foreach ($orderDetails as $detail) {
            $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);
        }

        // Obtener el detalle de la orden que contiene el id de la orden original en el nombre
        $orderDetailsWithOriginalId = $this->ordersLogic->getOrderDetailsWithOriginalId($id_order);

        foreach ($orderDetailsWithOriginalId as $detail) {
            $newOrderId = $detail->getOrder()->getIdOrder();
            $newOrder = $this->entityManagerInterface->getRepository(PsOrders::class)->find($newOrderId);

            if ($newOrder) {
                $newOrderData = $this->ordersLogic->generateOrderJSON($newOrder);
                $newOrderDetails = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
                    ->findBy(['idOrder' => $newOrderId]);

                foreach ($newOrderDetails as $newDetail) {
                    $newOrderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($newDetail);
                }

                $orderData['returns'][] = $newOrderData;
            }
        }

        $orderData['payment_amounts'] = $this->ordersLogic->generateJSONOrderPayments($id_order);

        // Devolver la respuesta como JSON
        return new JsonResponse($orderData, JsonResponse::HTTP_OK);
    }
    #[Route('/get_shop_orders', name: 'get_shop_orders', methods: ['POST'])]
    public function getOrdersByShop(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_shop'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        switch ($data['origin']) {
            case 'fajasmaylu':
                $orders = $this->emFajasMaylu->getRepository(PsOrdersFajasMaylu::class)->findOrdersByShop($data['id_shop']);
                break;
            case 'mayret':
                $orders = $this->entityManagerInterface->getRepository(PsOrders::class)->findOrdersByShop($data['id_shop']);
                break;
            case 'all':
                $ordersMaylu = $this->emFajasMaylu->getRepository(PsOrdersFajasMaylu::class)->findOrdersByShop($data['id_shop']);
                $ordersMayret = $this->entityManagerInterface->getRepository(PsOrders::class)->findOrdersByShop($data['id_shop']);
                $orders = array_merge($ordersMayret, $ordersMaylu);
                break;
            default:
            $orders = $this->entityManagerInterface->getRepository(PsOrders::class)->findOrdersByShop($data['id_shop']);

        }

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
        if (!isset($data['license'], $data['date2'])) {
            return new JsonResponse(
                ['status' => 'error', 'message' => 'Invalid data provided']
            );
        }

        if($data['date1'] == null)
        {
            $posSessions = $this->entityManagerInterface->getRepository(LpPosSessions::class)
                ->findOneActiveByLicense($data['license']);
            $data['date1'] = $posSessions->getDateAdd()->format('Y-m-d');
        }

        $posOrders = $this->entityManagerInterface->getRepository(LpPosOrders::class)
            ->getAllByLicenseAndDate($data['license'], $data['date1'], $data['date2']);
        $responseData = [];
        foreach ($posOrders as $posOrder) {
            $order = $this->entityManagerInterface->getRepository(PsOrders::class)->find($posOrder->getIdOrder());
            $orderData = $this->ordersLogic->generateSaleReportOrderJSON($order, $posOrder);
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
