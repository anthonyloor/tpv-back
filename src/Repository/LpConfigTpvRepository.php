<?php

namespace App\Repository;

use App\Entity\LpConfigTpv;
use App\Entity\LpPosSessions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LpConfigTpvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LpConfigTpv::class); // Cambiado aquí
    }

    public function createNewTPVConfig(array $data): LpConfigTpv
    {
        $newTPVConfig = new LpConfigTpv();
        $newTPVConfig->setLicense($data['license']);
        $newTPVConfig->setIdCustomerDefault($data['id_customer_default']);
        $newTPVConfig->setIdAddressDeliveryDefault($data['id_address_delivery_default']);
        if (isset($data['allow_out_of_stock_sales'])) {
            $newTPVConfig->setAllowOutOfStockSales($data['allow_out_of_stock_sales']);
        }
        if (isset($data['ticket_text_header_1'])) {
            $newTPVConfig->setTicketTextHeader1($data['ticket_text_header_1']);
        }
        if (isset($data['ticket_text_header_2'])) {
            $newTPVConfig->setTicketTextHeader2($data['ticket_text_header_2']);
        }
        if (isset($data['ticket_text_footer_1'])) {
            $newTPVConfig->setTicketTextFooter1($data['ticket_text_footer_1']);
        }
        if (isset($data['ticket_text_footer_2'])) {
            $newTPVConfig->setTicketTextFooter2($data['ticket_text_footer_2']);
        }

        return $newTPVConfig;
    }

    public function updateTPVConfig(array $data): ?LpConfigTpv
    {
        // Buscar la configuración existente por el campo 'license'
        $tpvConfig = $this->findOneBy(['license' => $data['license']]);

        if (!$tpvConfig) {
            // Si no se encuentra, devolver null o lanzar una excepción, según tu preferencia
            return null;
        }

        // Actualizar los campos con los datos proporcionados
        if (isset($data['id_customer_default'])) {
            $tpvConfig->setIdCustomerDefault($data['id_customer_default']);
        }
        if (isset($data['id_address_delivery_default'])) {
            $tpvConfig->setIdAddressDeliveryDefault($data['id_address_delivery_default']);
        }
        if (isset($data['allow_out_of_stock_sales'])) {
            $tpvConfig->setAllowOutOfStockSales($data['allow_out_of_stock_sales']);
        }
        if (isset($data['ticket_text_header_1'])) {
            $tpvConfig->setTicketTextHeader1($data['ticket_text_header_1']);
        }
        if (isset($data['ticket_text_header_2'])) {
            $tpvConfig->setTicketTextHeader2($data['ticket_text_header_2']);
        }
        if (isset($data['ticket_text_footer_1'])) {
            $tpvConfig->setTicketTextFooter1($data['ticket_text_footer_1']);
        }
        if (isset($data['ticket_text_footer_2'])) {
            $tpvConfig->setTicketTextFooter2($data['ticket_text_footer_2']);
        }

        // Retornar la entidad actualizada
        return $tpvConfig;
    }
}
