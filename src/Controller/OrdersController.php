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

    #[Route('/crete_order', name: 'crete_order', methods: ['POST'])]
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

        foreach($data['order_details'] as $orderDetailData)
        {
            $orderDetail = $this->generateOrderDetail($data, $orderDetailData, $newPsOrder);
            $this->entityManagerInterface->persist($orderDetail);
        }
        $this->entityManagerInterface->flush();

        return new JsonResponse(['status' => 'OK', 'message' => 'Order created with id'.$newPsOrder->getIdOrder()]);
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
        $orderDetail->setProductQuantity( $orderDetailData['product_quantity']);
        $orderDetail->setProductPrice( $orderDetailData['product_price']);
        $orderDetail->setProductEan13( $orderDetailData['product_ean13']);
        $orderDetail->setProductReference( $orderDetailData['product_reference']);
        $orderDetail->setTotalPriceTaxIncl( $orderDetailData['total_price_tax_incl']);
        $orderDetail->setTotalPriceTaxExcl( $orderDetailData['total_price_tax_excl']);
        $orderDetail->setUnitPriceTaxExcl( $orderDetailData['unit_price_tax_excl']);
        $orderDetail->setUnitPriceTaxIncl( $orderDetailData['unit_price_tax_incl']);
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
        ->findOneBy(['id_product' => $orderDetailData['product_id']]);
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
        // Genera una clave segura utilizando una combinación del ID del cliente y una clave secreta
        $secret = 'my_secret_key'; // Puedes cambiarla o tomarla de parámetros de configuración
        return hash('sha256', $customerId . $secret . uniqid((string) $customerId, true));
    }
}