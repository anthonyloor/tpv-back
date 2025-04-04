<?php

namespace App\Redsys;

use App\Redsys\Service\Impl\RESTOperationService;
use App\Redsys\Utils\RESTLogger;

class RedsysService
{
    private $merchantCode;
    private $merchantKey = "your_merchant_key"; // Cambiar a tu clave de comercio

    public function __construct(/*string $merchantCode, string $merchantKey*/)
    {
        // $this->merchantCode = $merchantCode;
        // $this->merchantKey = $merchantKey;

        // Inicializar logs si es necesario
        RESTLogger::initialize(__DIR__ . "/../../var/logs/", RESTLogger::$ERROR);
    }

    public function createPaymentRequest(float $amount, string $order)
    {
        $service = new RESTOperationService(
            $this->merchantKey,
            "TEST", // Cambiar a "PROD" para producción
        );

        
        $data = [
            "DS_MERCHANT_AMOUNT" => $amount * 100,  // Convertimos a céntimos
            "DS_MERCHANT_ORDER" => $order,
            "DS_MERCHANT_MERCHANTCODE" => "1a5fb1a4b27813b1d9fd", // Cambiar a tu código de comercio
            "DS_MERCHANT_CURRENCY" => "978", // Euro
            "DS_MERCHANT_TRANSACTIONTYPE" => "0",
            "DS_MERCHANT_TERMINAL" => "1"
        ];

        return $service->sendOperation($data);
    }
}
