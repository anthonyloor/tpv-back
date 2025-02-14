<?php

namespace App\Logic;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\PsOrders;
use App\Entity\PsOrderDetail;
use App\Entity\PsStockAvailable;
use App\Entity\LpPosSessions;
use App\Entity\LpPosOrders;

class OrdersLogic
{

    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function generateOrder($data): PsOrders
    {
        $newPsOrder = new PsOrders();

        $newPsOrder->setReference($this->generateUniqueOrderReference());
        $newPsOrder->setIdShopGroup(1);
        $newPsOrder->setIdShop($data['id_shop']);
        $newPsOrder->setIdCarrier(0);
        $newPsOrder->setIdLang(1);
        $newPsOrder->setIdCustomer($data['id_customer']);
        $newPsOrder->setIdCart(0);
        $newPsOrder->setIdCurrency(1);
        $newPsOrder->setIdAddressDelivery($data['id_address_delivery']);
        $newPsOrder->setIdAddressInvoice($data['id_address_delivery']);
        $newPsOrder->setCurrentState(19);
        $newPsOrder->setSecureKey($this->generateSecureKey($data['id_customer']));
        $newPsOrder->setPayment($data['payment']);
        $newPsOrder->setModule("LP-TPV");
        $newPsOrder->setTotalPaid($data['total_paid']);
        $newPsOrder->setTotalPaidTaxIncl($data['total_paid']);
        $newPsOrder->setTotalPaidTaxExcl($data['total_paid_tax_excl']);
        $newPsOrder->setTotalPaidReal($data['total_paid']);
        $newPsOrder->setTotalProducts($data['total_products']);
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
        $orderDetail->setReductionAmountTaxIncl(0);
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

    public function generatePosOrder($data, $psOrder): LpPosOrders
    {
        $license_param = $data['license'];
        $pos_session = $this->entityManagerInterface->getRepository(LpPosSessions::class)
            ->findOneActiveByLicense($license_param);

        $newPosOrder = new LpPosOrders();

        $newPosOrder->setIdOrder($psOrder->getIdOrder());
        $newPosOrder->setIdPosSession($pos_session->getIdPosSessions());
        $newPosOrder->setIdShop($data['id_shop']);
        $newPosOrder->setLicense($data['license']);
        $newPosOrder->setIdEmployee($data['id_employee']);
        $newPosOrder->setTotalAmount($data['total_paid']);
        $newPosOrder->setTotalCash($data['total_cash']);
        $newPosOrder->setTotalCard($data['total_card']);
        $newPosOrder->setTotalBizum($data['total_bizum']);

        $newPosOrder->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));

        return $newPosOrder;
    }

    public function generateOrderJSON($order)
    {
        $orderData = [
            'id_order' => $order->getIdOrder(),
            'id_shop' => $order->getIdShop(),
            'id_customer' => $order->getIdCustomer(),
            'id_address_delivery' => $order->getIdAddressDelivery(),
            'payment' => $order->getPayment(),
            'total_paid' => $order->getTotalPaid(),
            'total_paid_tax_excl' => $order->getTotalPaidTaxExcl(),
            'total_products' => $order->getTotalProducts(),
            'date_add' => $order->getDateAdd()->format('Y-m-d H:i:s'),
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

    public function generateOrderDetailJSON($detail)
    {

        $stockAvailable = $this->entityManagerInterface->getRepository(PsStockAvailable::class)
            ->findOneByProductAttributeShop(
                $detail->getProductId(),
                $detail->getProductAttributeId(),
                $detail->getIdShop()
            );

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
            'id_shop' => $detail->getIdShop()
        ];

        return $orderDetail;
    }
}
