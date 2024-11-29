<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\PsOrders;
use App\Entity\PsOrderDetail;
use App\Entity\PsStockAvailable;

class OrdersController
{
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
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
                $data['total_paid'],
                $data['total_paid_tax_excl'],
                $data['total_products'],
                $data['order_details']
            )
        ) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $newPsOrder = $this->generateOrder($data);
        $this->entityManagerInterface->persist($newPsOrder);
        $this->entityManagerInterface->flush();

        foreach ($data['order_details'] as $orderDetailData) {
            $orderDetail = $this->generateOrderDetail($data, $orderDetailData, $newPsOrder);
            $this->entityManagerInterface->persist($orderDetail);
            $this->updateProductStock($orderDetailData); // Llamamos a la función de actualización de stock
        }
        $this->entityManagerInterface->flush();

        return new JsonResponse(['status' => 'OK', 'message' => 'Order created with id' . $newPsOrder->getIdOrder()]);
    }
    #[Route('/get_order', name: 'get_order', methods: ['GET'])]
    public function createOrder(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['id_order'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $order = $this->entityManagerInterface->getRepository(PsOrders::class)->find($id_order);
        if (!$order) {
            return new JsonResponse(['status' => 'error', 'message' => 'Order not found'], JsonResponse::HTTP_OK);
        }
        // Construir la respuesta con la información de la orden
        $orderData = [
            'id_shop' => $order->getIdShop(),
            'id_customer' => $order->getIdCustomer(),
            'id_address_delivery' => $order->getIdAddressDelivery(),
            'payment' => $order->getPayment(),
            'total_paid' => $order->getTotalPaid(),
            'total_paid_tax_excl' => $order->getTotalPaidTaxExcl(),
            'total_products' => $order->getTotalProducts(),
            'order_details' => []
        ];
        // Obtener los detalles de la orden
        $orderDetails = $this->entityManagerInterface->getRepository(PsOrderDetail::class)
            ->findBy(['idOrder' => $idOrder]);

        // Procesar los detalles de la orden
        foreach ($orderDetails as $detail) {
            $orderData['order_details'][] = [
                'product_id' => $detail->getProductId(),
                'product_attribute_id' => $detail->getProductAttributeId(),
                'stock_available_id' => $detail->getStockAvailableId(),
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
        }

        // Devolver la respuesta como JSON
        return new JsonResponse($orderData, JsonResponse::HTTP_OK);
    }

    private function generateOrder($data): PsOrders
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
        $newPsOrder->setInvoiceDate(new \DateTime());
        $newPsOrder->setValid(1);
        $newPsOrder->setDateAdd(new \DateTime());
        $newPsOrder->setDateUpd(new \DateTime());
        $newPsOrder->setTotalDiscounts(0);
        $newPsOrder->setTotalDiscountsTaxExcl(0);
        $newPsOrder->setTotalDiscountsTaxIncl(0);
        //Preguntar por estos dos
        $newPsOrder->setRoundMode(2);
        $newPsOrder->setRoundType(2);

        return $newPsOrder;
    }

    private function generateOrderDetail($data, $orderDetailData, $newPsOrder): PsOrderDetail
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
    private function generateUniqueOrderReference(): string
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

    private function generateSecureKey(int $customerId): string
    {
        $secret = 'my_secret_key';
        return hash('sha256', $customerId . $secret . uniqid((string) $customerId, true));
    }

    private function updateProductStock($orderDetailData)
    {
        // Buscar el registro de stock para el producto
        $productStock = $this->entityManagerInterface->getRepository(PsStockAvailable::class)
            ->findOneBy(['id_stock_available' => $orderDetailData['stock_available_id']]);

        // Si existe, reducir el stock disponible en función de la cantidad de pedido
        if ($productStock) {
            $newQuantity = max(0, $productStock->getQuantity() - $orderDetailData['product_quantity']);
            $productStock->setQuantity($newQuantity);
            $this->entityManagerInterface->persist($productStock); // Persistir los cambios
        }
    }
}
