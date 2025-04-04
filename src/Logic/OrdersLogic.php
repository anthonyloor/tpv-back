<?php

namespace App\Logic;

use App\Utils\Constants\Entity\PsOrderFields;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\PsOrders;
use App\Entity\PsOrderDetail;
use App\Entity\PsStockAvailable;
use App\Entity\LpPosSessions;
use App\Entity\LpPosOrders;
use App\Entity\PsOrderHistory;
use App\Entity\PsOrderPayment;
use App\Entity\PsOrderState;
use App\Entity\PsCustomer;
use App\Entity\PsAddress;
use App\Entity\PsOrderCartRule;

use App\EntityFajasMaylu\PsOrders as PsOrdersFajasMaylu;
use App\EntityFajasMaylu\PsOrderDetail as PsOrderDetailFajasMaylu;
use App\EntityFajasMaylu\PsOrderCartRule as PsOrderCartRuleFajasMaylu;

use App\EntityFajasMaylu\PsStockAvailable as PsStockAvailableFajasMaylu;
use Doctrine\Persistence\ManagerRegistry;

class OrdersLogic
{

    private $entityManagerInterface;
    private $emFajasMaylu;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManagerInterface = $doctrine->getManager('default');
        $this->emFajasMaylu = $doctrine->getManager('fajas_maylu');
    }

    public function generateOrder($data): PsOrders
    {
        $newPsOrder = new PsOrders();

        $newPsOrder->setReference($this->generateUniqueOrderReference());
        $newPsOrder->setIdShopGroup(1);
        $newPsOrder->setIdShop($data['id_shop']);
        $newPsOrder->setIdCarrier(0);
        $newPsOrder->setIdLang(1);

        $customer = $this->entityManagerInterface->getRepository(PsCustomer::class)->find($data['id_customer']);
        $newPsOrder->setCustomer($customer);

        $newPsOrder->setIdCart(0);
        $newPsOrder->setIdCurrency(1);

        $addressDelivery = $this->entityManagerInterface->getRepository(PsAddress::class)->find($data['id_address_delivery']);
        $newPsOrder->setAddressDelivery($addressDelivery);

        $newPsOrder->setIdAddressInvoice($data['id_address_delivery']);
        $orderState = $this->entityManagerInterface->getRepository(PsOrderState::class)->find(19);
        $newPsOrder->setCurrentState($orderState);
        $newPsOrder->setSecureKey($this->generateSecureKey($data['id_customer']));
        $newPsOrder->setPayment($data['payment']);
        $newPsOrder->setModule("LP-TPV");
        $newPsOrder->setTotalPaid($data['total_paid']);
        $newPsOrder->setTotalPaidTaxIncl($data['total_paid']);
        $newPsOrder->setTotalPaidTaxExcl($data['total_paid_tax_excl']);
        $newPsOrder->setTotalPaidReal($data['total_paid']);
        $newPsOrder->setTotalProducts($data['total_products']);
        $newPsOrder->setTotalShipping(0);
        $newPsOrder->setTotalProductsWt($data['total_paid']);
        $newPsOrder->setInvoiceNumber(0);
        $newPsOrder->setInvoiceDate(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $newPsOrder->setValid(1);
        $newPsOrder->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $newPsOrder->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $newPsOrder->setTotalDiscounts($data['total_discounts']);
        $newPsOrder->setTotalDiscountsTaxExcl($data['total_discounts_tax_excl']);
        $newPsOrder->setTotalDiscountsTaxIncl($data['total_discounts']);
        //Preguntar por estos dos
        $newPsOrder->setRoundMode(2);
        $newPsOrder->setRoundType(2);

        return $newPsOrder;
    }

    public function generateOrderDetail($data, $orderDetailData, $newPsOrder): PsOrderDetail
    {
        $orderDetail = new PsOrderDetail();
        $orderDetail->setOrder($newPsOrder);
        $orderDetail->setIdShop($data['id_shop']);
        $orderDetail->setProductId($orderDetailData['product_id']);
        $orderDetail->setProductAttributeId($orderDetailData['product_attribute_id']);
        $orderDetail->setProductName($orderDetailData['product_name']);
        $orderDetail->setProductQuantity($orderDetailData['product_quantity']);
        $orderDetail->setProductPrice($orderDetailData['product_price']);
        $orderDetail->setProductEan13($orderDetailData['product_ean13']);
        $orderDetail->setProductReference($orderDetailData['product_reference']);
        $orderDetail->setTotalPriceTaxIncl($orderDetailData['total_price_tax_incl']);
        $orderDetail->setTotalPriceTaxExcl($orderDetailData['total_price_tax_excl']);
        $orderDetail->setUnitPriceTaxExcl($orderDetailData['unit_price_tax_excl']);
        $orderDetail->setUnitPriceTaxIncl($orderDetailData['unit_price_tax_incl']);
        $orderDetail->setReductionPercent(0);
        $orderDetail->setReductionAmount(0);
        $orderDetail->setReductionAmountTaxExcl(0);
        $orderDetail->setReductionAmountTaxIncl($orderDetailData['reduction_amount_tax_incl']);
        $orderDetail->setGroupReduction(0);
        $orderDetail->setProductQuantityDiscount(0);
        $orderDetail->setTaxName("IVA");
        $orderDetail->setTaxRate(21);
        $orderDetail->setOriginalProductPrice(0);

        $productStock = $this->entityManagerInterface->getRepository(PsStockAvailable::class)
            ->findOneBy(['id_stock_available' => $orderDetailData['stock_available_id']]);
        if ($productStock) {
            $orderDetail->setProductQuantityInStock($productStock->getQuantity());
        } else {
            $orderDetail->setProductQuantityInStock(0); // Si no se encuentra, asigna 0
        }

        return $orderDetail;
    }
    public function generateUniqueOrderReference(): string
    {
        $length = 9;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            // Genera una referencia aleatoria de 9 caracteres
            $reference = '';
            for ($i = 0; $i < $length; $i++) {
                $reference .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Verifica si la referencia ya existe en la base de datos
            $existingOrder = $this->entityManagerInterface->getRepository(PsOrders::class)
                ->findOneBy(['reference' => $reference]);
        } while ($existingOrder !== null); // Repetir si ya existe

        return $reference;
    }

    public function generateSecureKey(int $customerId): string
    {
        $secret = 'my_secret_key';
        return hash('sha256', $customerId . $secret . uniqid((string) $customerId, true));
    }

    public function updateProductStock($orderDetailData)
    {
        // Buscar el registro de stock para el producto
        $productStock = $this->entityManagerInterface->getRepository(PsStockAvailable::class)
            ->findOneBy(['id_stock_available' => $orderDetailData['stock_available_id']]);

        // Si existe, reducir el stock disponible en función de la cantidad de pedido
        if ($productStock) {
            $newQuantity = $productStock->getQuantity() - $orderDetailData['product_quantity'];
            $productStock->setQuantity($newQuantity);
            $this->entityManagerInterface->persist($productStock); // Persistir los cambios
        }
    }

    public function generatePosOrder($id_shop, $license, $id_employee, $total_paid, $total_cash, $total_card, $total_bizum, $id_order, string $origin = "mayret"): LpPosOrders
    {
        $license_param = $license;
        $pos_session = $this->entityManagerInterface->getRepository(LpPosSessions::class)
            ->findOneActiveByLicense($license_param);

        $newPosOrder = new LpPosOrders();

        $newPosOrder->setIdOrder($id_order);
        $newPosOrder->setIdPosSession($pos_session->getIdPosSessions());
        $newPosOrder->setIdShop($id_shop);
        $newPosOrder->setLicense($license);
        $newPosOrder->setIdEmployee($id_employee);
        $newPosOrder->setTotalAmount($total_paid);
        $newPosOrder->setTotalCash($total_cash);
        $newPosOrder->setTotalCard($total_card);
        $newPosOrder->setTotalBizum($total_bizum);
        $newPosOrder->setOrigin($origin);

        $newPosOrder->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $this->entityManagerInterface->persist($newPosOrder); 
        $this->entityManagerInterface->flush(); 
        return $newPosOrder;
    }

    public function generateOrderJSON($order)
    {
        $posOrder = $this->entityManagerInterface->getRepository(LpPosOrders::class)
            ->findOneBy(['id_order' => $order->getIdOrder()]);

        $customer = $order->getCustomer()->getIdCustomer() == 0 ? null : $order->getCustomer();
        $addressDelivery = $order->getAddressDelivery()->getIdAddress() == 0 ? null : $order->getAddressDelivery();
        $orderData = [
            PsOrderFields::ID_ORDER => $order->getIdOrder(),
            PsOrderFields::ID_SHOP => $order->getIdShop(),
            'id_customer' => $customer?->getIdCustomer(),
            'customer_name' => $customer?->getFirstname() . ' ' . $customer?->getLastname(),
            'id_employee' => $posOrder?->getIdEmployee(),
            'id_address_delivery' => $addressDelivery?->getIdAddress(),
            'address_delivery_name' => $addressDelivery?->getAddress1(),
            PsOrderFields::PAYMENT => $order->getPayment(),
            PsOrderFields::TOTAL_PAID => $order->getTotalPaid(),
            PsOrderFields::TOTAL_PAID_TAX_EXCL => $order->getTotalPaidTaxExcl(),
            PsOrderFields::TOTAL_PRODUCTS => $order->getTotalProducts(),
            PsOrderFields::TOTAL_SHIPPING => $order->getTotalShipping(),
            PsOrderFields::CURRENT_STATE => $order->getCurrentState()->getIdOrderState(),
            'current_state_name' => $order->getCurrentStateName(),
            PsOrderFields::VALID => $order->getValid(),
            PsOrderFields::DATE_ADD => $order->getDateAdd()->format('Y-m-d H:i:s'),
            'origin' => $order->getOrigin(),
            'order_details' => []
        ];

        return $orderData;
    }

    public function generateSaleReportOrderJSON($order, $posOrder)
    {
        $orderData = $this->generateOrderJson($order);

        $orderData['total_cash'] = $posOrder->getTotalCash();
        $orderData['total_card'] = $posOrder->getTotalCard();
        $orderData['total_bizum'] = $posOrder->getTotalBizum();

        return $orderData;
    }

    public function generateOrderDetailJSON($detail, string $origin)
    {
        switch ($origin) {
            case "fajas_maylu":
                $stockAvailable = $this->emFajasMaylu->getRepository(PsStockAvailableFajasMaylu::class)
                    ->findOneByProductAttributeShop(
                        $detail->getProductId(),
                        $detail->getProductAttributeId(),
                        $detail->getIdShop()
                    );
                break;
            case "mayret":
                $stockAvailable = $this->entityManagerInterface->getRepository(PsStockAvailable::class)
                    ->findOneByProductAttributeShop(
                        $detail->getProductId(),
                        $detail->getProductAttributeId(),
                        $detail->getIdShop()
                    );
                break;
            default:
                $stockAvailable = $this->entityManagerInterface->getRepository(PsStockAvailable::class)
                    ->findOneByProductAttributeShop(
                        $detail->getProductId(),
                        $detail->getProductAttributeId(),
                        $detail->getIdShop()
                    );
        }

        $stock_available_id = $stockAvailable ? $stockAvailable->getIdStockAvailable() : null;

        $orderDetail = [
            'product_id' => $detail->getProductId(),
            'product_attribute_id' => $detail->getProductAttributeId(),
            'stock_available_id' => $stock_available_id,
            'product_name' => $detail->getProductName(),
            'product_quantity' => $detail->getProductQuantity(),
            'product_price' => $detail->getProductPrice(),
            'product_ean13' => $detail->getProductEan13(),
            'product_reference' => $detail->getProductReference(),
            'total_price_tax_incl' => $detail->getTotalPriceTaxIncl(),
            'total_price_tax_excl' => $detail->getTotalPriceTaxExcl(),
            'unit_price_tax_incl' => $detail->getUnitPriceTaxIncl(),
            'unit_price_tax_excl' => $detail->getUnitPriceTaxExcl(),
            'reduction_amount_tax_incl' => $detail->getReductionAmountTaxIncl(),
            'id_shop' => $detail->getIdShop()
        ];

        return $orderDetail;
    }

    public function getOrderDetailsWithOriginalId($id_order,$origin)
    {
        if($origin == "mayret"){
            $oneYearAgo = (new \DateTime('now', new \DateTimeZone('Europe/Berlin')))->modify('-1 year');

            return $this->entityManagerInterface->createQueryBuilder()
                ->select('od')
                ->from(PsOrderDetail::class, 'od')
                ->where('od.product_name LIKE :id_order')
                ->setParameter('id_order', '%' . $id_order . '%')
                ->getQuery()
                ->getResult();
        }
        return null;
    }

    public function generateJSONOrderPayments($idOrder): array
    {
        $posOrder = $this->entityManagerInterface->getRepository(LpPosOrders::class)
            ->findOneBy(['id_order' => $idOrder]);

        if (!$posOrder) {
            $payments = [
                'total_cash' => 0,
                'total_card' => 0,
                'total_bizum' => 0
            ];
        }else{
            $payments = [
                'total_cash' => $posOrder->getTotalCash(),
                'total_card' => $posOrder->getTotalCard(),
                'total_bizum' => $posOrder->getTotalBizum()
            ];
        }

        return $payments;
    }

    public function generateOrderHistory($psOrder, $idEmployee): PsOrderHistory
    {
        $newOrderHistory = new PsOrderHistory();

        $newOrderHistory->setIdEmployee($idEmployee);
        $newOrderHistory->setIdOrder($psOrder->getIdOrder());
        $newOrderHistory->setIdOrderState($psOrder->getCurrentState()->getIdOrderState());
        $newOrderHistory->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $this->entityManagerInterface->persist($newOrderHistory);
        $this->entityManagerInterface->flush();
        return $newOrderHistory;
    }

    public function createOrderPayment($psOrder, string $paymentMethod, float $amount): PsOrderPayment
    {
        $newOrderPayment = new PsOrderPayment();
        $newOrderPayment->setOrderReference($psOrder->getReference());
        $newOrderPayment->setIdCurrency($psOrder->getIdCurrency());
        $newOrderPayment->setAmount($amount); // Usamos el monto específico de cada método
        $newOrderPayment->setPaymentMethod($paymentMethod);
        $newOrderPayment->setConversionRate(1);
        $newOrderPayment->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));

        $this->entityManagerInterface->persist($newOrderPayment);
        $this->entityManagerInterface->flush();

        return $newOrderPayment;
    }

    public function generateOrderPayments($psOrder, array $data)
    {
        $paymentMethods = [
            'tarjeta' => $data['total_card'] ?? 0,
            'bizum' => $data['total_bizum'] ?? 0,
            'efectivo' => $data['total_cash'] ?? 0
        ];

        foreach ($paymentMethods as $method => $amount) {
            if ($amount > 0) { // Solo creamos pagos si hay un monto mayor a 0
                $this->createOrderPayment($psOrder, $method, $amount);
            }
        }
    }

    public function getOrderbyIdAndOrigin($origin, $id_order)
    {
        $order = null;
        switch ($origin) {
            case 'fajasmaylu':
                $order = $this->emFajasMaylu->getRepository(PsOrdersFajasMaylu::class)->findById($id_order);
                break;
            case 'mayret':
                $order = $this->entityManagerInterface->getRepository(PsOrders::class)->findById($id_order);
                break;
        }
        return $order;
    }
    public function getOrderDetailsByOrderIdAndOrigin($origin, $id_order)
    {
        $orderDetails = null;
        switch ($origin) {
            case 'fajasmaylu':
                // Obtener los detalles de la orden
                $orderDetails = $this->emFajasMaylu->getRepository(PsOrderDetailFajasMaylu::class)
                ->findByOrderId($id_order);
                break;
            case 'mayret':
                // Obtener los detalles de la orden
                $orderDetails = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
                ->findByOrderId($id_order);
        }
        return $orderDetails;
    }

    public function getOrdersByShopAndOrigin($data):array
    {
        $orders = null;
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
        return $orders;
    }
}
