<?php

namespace App\Controller;

use App\Entity\LpPosOrders;

use App\Logic\CartRuleLogic;
use App\Logic\StockControllLogic;
use App\Utils\Logger\Logger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Logic\WareHouseMovementLogic;
use App\Utils\Constants\DatabaseManagers;
use App\Utils\Constants\HttpMessages;

use App\Entity\PsOrders;
use App\Entity\PsOrderDetail;
use App\Logic\OrdersLogic;
use App\Entity\LpPosSessions;
use App\Entity\PsCartRule;
use App\Entity\PsCartRuleLang;
use App\Entity\PsOrderCartRule;
use App\Entity\LpControlStock;
use App\Entity\PsOrderState;

class OrdersController
{
    private $entityManagerInterface;
    private OrdersLogic $ordersLogic;
    private CartRuleLogic $cartRuleLogic;
    private StockControllLogic $stockControllLogic;
    private $wareHouseMovementLogic;
    private $logger;

    public function __construct(ManagerRegistry $doctrine, OrdersLogic $ordersLogic, CartRuleLogic $cartRuleLogic, StockControllLogic $stockControllLogic, WareHouseMovementLogic $wareHouseMovementLogic, Logger $logger)
    {
        $this->entityManagerInterface = $doctrine->getManager(DatabaseManagers::MAYRET_MANAGER);
        $this->ordersLogic = $ordersLogic;
        $this->cartRuleLogic = $cartRuleLogic;
        $this->stockControllLogic = $stockControllLogic;
        $this->wareHouseMovementLogic = $wareHouseMovementLogic;
        $this->logger = $logger;
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
            return new JsonResponse(['status' => 'error', 'message' => HttpMessages::INVALID_DATA], Response::HTTP_BAD_REQUEST);
        }

        $this->logger->log('-------------------------------INICIO---------------------------------------');

        $newPsOrder = $this->ordersLogic->generateOrder($data);
        $this->entityManagerInterface->persist($newPsOrder);
        $this->entityManagerInterface->flush();

        $newPosOrder = $this->ordersLogic->generatePosOrder(
            $data['id_shop'],$data['license'],$data['id_employee'], $data['total_paid'],$data['total_cash'],
            $data['total_card'],$data['total_bizum'], $newPsOrder->getIdOrder(), $data['num_pedido'],$data['identificador_rts'],
            $data['cash_recived'], $data['cash_returned']
        );

        $this->ordersLogic->updatePosSessionsTotalPayments($data);

        $orderHistory = $this->ordersLogic->generateOrderHistory($newPsOrder, $data['id_employee']);
        $this->ordersLogic->generateOrderPayments($newPsOrder, $data);

        foreach ($data['order_details'] as $orderDetailData) {
            $orderDetail = $this->ordersLogic->generateOrderDetail($data, $orderDetailData, $newPsOrder);
            $this->ordersLogic->updateProductStock($orderDetailData); // Llamamos a la función de actualización de stock
            if (isset($orderDetailData['id_control_stock'])) {
                $controlStock = $this->entityManagerInterface->getRepository(LpControlStock::class)->find($orderDetailData['id_control_stock']);
                if ($orderDetailData['product_quantity'] > 0) {
                    $this->stockControllLogic->createControlStockHistory($orderDetailData['id_control_stock'], 'Venta de producto', 'Venta', $data['id_shop'],$orderDetail->getIdOrderDetail());
                    $controlStock->setActive(active: false);
                } else {
                    $this->stockControllLogic->createControlStockHistory($orderDetailData['id_control_stock'], 'Devolución de producto', 'Devolución', $data['id_shop'],$orderDetail->getIdOrderDetail());
                    $controlStock->setActive(active: true);
                    $controlStock->setIdShop($data['id_shop']);
                }
                $controlStock->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
                $this->entityManagerInterface->persist($controlStock);
            }
        }

        $this->entityManagerInterface->flush();

        if (isset($data['discounts'])) {
            $newCartRule = null;
            foreach ($data['discounts'] as $discount) {
                $cart_rule = $this->entityManagerInterface->getRepository(PsCartRule::class)->findOneBy(['code' => $discount['code'], 'active' => true]);
                if (!$cart_rule) {
                    return new JsonResponse(['status' => 'error', 'message' => HttpMessages::INVALID_VOUCHER], Response::HTTP_BAD_REQUEST);
                }
                $cart_rule->setQuantity($cart_rule->getQuantity() - 1);
                $cart_rule->setActive(false);
                $this->entityManagerInterface->persist($cart_rule);
                $this->cartRuleLogic->generateOrderCartRule($newPsOrder, $cart_rule, $discount);
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
                        'date_from' => (new \DateTime('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d H:i:s'),
                        'date_to' => (new \DateTime('now', new \DateTimeZone('Europe/Berlin')))->modify('+6 months')->format('Y-m-d H:i:s'),
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
        $this->logger->log('-------------------------------FIN---------------------------------------');
        return new JsonResponse(data: ['status' => 'OK', 'message' => 'Order created with id ' . $newPsOrder->getIdOrder()]);

    }

    #[Route('/get_order', name: 'get_order', methods: ['POST'])]
    public function getOrderById(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_order'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], Response::HTTP_BAD_REQUEST);
        }
        $id_order = $data['id_order'];

        $order = $this->ordersLogic->getOrderById($id_order);
        $orderCartRules = $this->cartRuleLogic->getCartRulesByOrderId($id_order);
        $orderDetails = $this->ordersLogic->getOrderDetailsByOrderId($id_order);
        if (!$order) {
            return new JsonResponse(['status' => 'error', 'message' => 'Order not found'], Response::HTTP_OK);
        }
        // Construir la respuesta con la información de la orden
        $orderData = $this->ordersLogic->generateOrderJSON($order);
        if($orderCartRules != null)
        {
            $orderData  = $this->cartRuleLogic->generateCartRulesJSON($orderData, $orderCartRules);
        }

        // Procesar los detalles de la orden
        foreach ($orderDetails as $detail) {
            $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);

        }

        // Obtener el detalle de la orden que contiene el id de la orden original en el nombre
        $orderDetailsWithOriginalId = $this->ordersLogic->getOrderDetailsWithOriginalId($id_order);

        if ($orderDetailsWithOriginalId != null) {
            foreach ($orderDetailsWithOriginalId as $detail) {
                $newOrderId = $detail->getOrder()->getIdOrder();
                $newOrder = $this->entityManagerInterface->getRepository(PsOrders::class)->find($newOrderId);

                if ($newOrder) {
                    $newOrderData = $this->ordersLogic->generateOrderJSON($newOrder);
                    $newOrderDetails = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
                        ->findByOrderId($newOrderId);

                    foreach ($newOrderDetails as $newDetail) {
                        $newOrderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($newDetail);

                    }

                    $orderData['returns'][] = $newOrderData;
                }
            }
        }

        $orderData['payment_amounts'] = $this->ordersLogic->generateJSONOrderPayments($id_order);

        // Devolver la respuesta como JSON
        return new JsonResponse($orderData, Response::HTTP_OK);
    }
    #[Route('/get_shop_orders', name: 'get_shop_orders', methods: ['POST'])]
    public function getOrdersByShop(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_shop'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], Response::HTTP_BAD_REQUEST);
        }

        $orders = $this->ordersLogic->getOrdersByShop($data['id_shop']);

        if (!$orders) {
            return new JsonResponse(['status' => 'error', 'message' => 'No orders found'], Response::HTTP_OK);
        }

        $responseData = [];

        foreach ($orders as $order) {
            $orderData = $this->ordersLogic->generateOrderJSON($order);
            $orderDetails = $this->ordersLogic->getOrderDetailsByOrderId($order->getIdOrder());

            foreach ($orderDetails as $detail) {
                $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);
            }
            $responseData[] = $orderData;
        }

        // Devolver la respuesta con las órdenes como JSON
        return new JsonResponse($responseData, Response::HTTP_OK);
    }

    #[Route('/get_last_orders_by_customer', name: 'get_last_orders_by_customer', methods: ['POST'])]
    public function getLastOrdersByCustomer(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_customer'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], Response::HTTP_BAD_REQUEST);
        }

        $orders = $this->ordersLogic->getLastOrdersByCustomer($data['id_customer']);

        if (!$orders) {
            return new JsonResponse(['status' => 'error', 'message' => 'No orders found'], Response::HTTP_OK);
        }

        $responseData = [];
        foreach ($orders as $order) {
            $orderData = $this->ordersLogic->generateOrderJSON($order);
            $orderDetails = $this->ordersLogic->getOrderDetailsByOrderId($order->getIdOrder());

            foreach ($orderDetails as $detail) {
                $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);
            }

            $orderCartRules = $this->cartRuleLogic->getCartRulesByOrderId($order->getIdOrder());
            if ($orderCartRules != null) {
                $orderData = $this->cartRuleLogic->generateCartRulesJSON($orderData, $orderCartRules);
            }

            $responseData[] = $orderData;
        }

        return new JsonResponse($responseData, Response::HTTP_OK);
    }

    #[Route('/get_sale_report_orders', name: 'get_sale_report_orders', methods: ['POST'])]
    public function getSaleReportOrders(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['licenses'], $data['date2'])) {
            return new JsonResponse(
                ['status' => 'error', 'message' => 'Invalid data provided']
            );
        }

        $responseData = [];
        foreach ($data['licenses'] as $license) {
            if ($data['date1'] == null) {
            $posSessions = $this->entityManagerInterface->getRepository(LpPosSessions::class)
                ->findOneActiveByLicense($license);
            $data['date1'] = $posSessions->getDateAdd()->format('Y-m-d');
            }

            $posOrders = $this->entityManagerInterface->getRepository(LpPosOrders::class)
            ->getAllByLicenseAndDate($license, $data['date1'], $data['date2']);
            foreach ($posOrders as $posOrder) {
                $order = $this->ordersLogic->getOrderById($posOrder->getIdOrder());
                $orderData = $this->ordersLogic->generateSaleReportOrderJSON($order, $posOrder);
                $orderDetails = $this->ordersLogic->getOrderDetailsByOrderId($posOrder->getIdOrder());

                $orderCartRules = $this->cartRuleLogic->getCartRulesByOrderId($posOrder->getIdOrder());
                if($orderCartRules != null)
                {
                    $orderData = $this->cartRuleLogic->generateCartRulesJSON($orderData, $orderCartRules);
                }


                foreach ($orderDetails as $detail) {
                    $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);
                }
                $responseData[] = $orderData;
            }
        }

        return new JsonResponse($responseData, Response::HTTP_OK);

    }

    #[Route('/update_online_orders', name: 'update_online_orders', methods: ['POST'])]
    public function updateOnlineOrders(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_order'], $data['status'])) {
            return new JsonResponse(['status' => 'error', 'message' => HttpMessages::INVALID_DATA], Response::HTTP_BAD_REQUEST);
        }

        $newPosOrder = $this->ordersLogic->generatePosOrder(
            $data['id_shop'],$data['license'],$data['id_employee'], $data['total_paid'],$data['total_cash'],
            $data['total_card'],$data['total_bizum'], $data['id_order'], $data['num_pedido'], 
            $data['identificador_rts'], $data['cash_recived'], $data['cash_returned']
        );

        foreach ($data['shops'] as $shop) {
            $dataMovement = [
                'description' => 'Salida por la venta online del ticket #' . $data['id_order'],
                'id_shop_origin' => $shop['id_shop'],
                'type' => 'salida',
                'id_employee' => $data['id_employee'],
            ];
            $lpWarehouseMovement = $this->wareHouseMovementLogic->generateWareHouseMovement($dataMovement);
            foreach ($shop['products'] as $product) {
                $dataMovementDetail = [
                    'id_warehouse_movement' => $lpWarehouseMovement->getIdWarehouseMovement(),
                    'sent_quantity' => $product['quantity'],
                    'id_product' => $product['id_product'],
                    'id_product_attribute' => $product['id_product_attribute'],
                    'product_name' => $product['product_name'],
                    'ean13' => $product['ean13'],
                    'id_control_stock' => $product['id_control_stock'],
                ];
                $this->wareHouseMovementLogic->generateWareHouseMovementDetail($dataMovementDetail, $lpWarehouseMovement);
                if($product['id_control_stock'] != null)
                {
                    $controlStock = $this->entityManagerInterface->getRepository(LpControlStock::class)->find($product['id_control_stock']);
                    $this->stockControllLogic->createControlStockHistory($product['id_control_stock'], 'Venta de producto online', 'Venta', $shop['id_shop'],$product['id_order_detail']);
                    $controlStock->setActive( false);
                    $controlStock->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
                    $this->entityManagerInterface->persist($controlStock);
                    $this->entityManagerInterface->flush();
                }
            }
            $lpWarehouseMovement = $this->wareHouseMovementLogic->executeWareHouseMovement($lpWarehouseMovement);

        }

        $this->ordersLogic->updatePosSessionsTotalPayments($data);

        $order = $this->entityManagerInterface->getRepository(PsOrders::class)->findById($data['id_order']);
        $orderState = $this->entityManagerInterface->getRepository(PsOrderState::class)->findById($data['status']);
        $order->setCurrentState($orderState);
        $this->entityManagerInterface->persist($order);
        $this->entityManagerInterface->flush();

        return new JsonResponse(['status' => 'OK', 'message' => HttpMessages::ORDER_UPDATED . $data['id_order']]);
    }

    #[Route('/get_pos_session_sale_report_orders', name: 'get_pos_session_sale_report_orders', methods: ['POST'])]
    public function getPosSessionSaleReportOrder(Request $request): Response
    {
        $data = json_decode($request->getContent(),true);
        if (!isset($data['id_pos_session'])) {
            return new JsonResponse(['status' => 'error', 'message' => HttpMessages::INVALID_DATA], Response::HTTP_BAD_REQUEST);
        }
        $posSession = $this->entityManagerInterface->getRepository(LpPosSessions::class)->find($data['id_pos_session']);
        $posOrders = $this->entityManagerInterface->getRepository(LpPosOrders::class)->findBy(['id_pos_session' => $posSession->getIdPosSessions()]);
        foreach ($posOrders as $posOrder) {
            $order = $this->ordersLogic->getOrderById($posOrder->getIdOrder());
            $orderData = $this->ordersLogic->generateSaleReportOrderJSON($order, $posOrder);
            $orderDetails = $this->ordersLogic->getOrderDetailsByOrderId($posOrder->getIdOrder());

            $orderCartRules = $this->cartRuleLogic->getCartRulesByOrderId($posOrder->getIdOrder());
            if($orderCartRules != null)
            {
                $orderData = $this->cartRuleLogic->generateCartRulesJSON($orderData, $orderCartRules);
            }


            foreach ($orderDetails as $detail) {
                $orderData['order_details'][] = $this->ordersLogic->generateOrderDetailJSON($detail);
            }
            $responseData[] = $orderData;
        }
        return new JsonResponse($responseData, Response::HTTP_OK);
    }

    #[Route('/sales_returns', name: 'sales_returns')]
    public function getSalesReturns(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['search_term'])) {
            return new JsonResponse(['error' => 'Faltan parametros'], Response::HTTP_BAD_REQUEST);
        }

        $reference = $data['search_term'];

        $results = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
            ->getSalesReturnsByReference($reference);

        return new JsonResponse($results);
    }


}
