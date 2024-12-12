<?php

namespace App\Entity;

use App\Repository\LpConfigTpvRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpConfigTpvRepository::class)]
class LpConfigTpv
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 36)]
    private ?string $license = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_customer_default = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_address_delivery_default = null;

    #[ORM\Column(type: "boolean", options: ["default" => 0])]
    private bool $allow_out_of_stock_sales = false;

    #[ORM\Column(type: "string", length: 266, nullable: true)]
    private ?string $ticket_text_header_1 = null;

    #[ORM\Column(type: "string", length: 266, nullable: true)]
    private ?string $ticket_text_header_2 = null;

    #[ORM\Column(type: "string", length: 266, nullable: true)]
    private ?string $ticket_text_footer_1 = null;

    #[ORM\Column(type: "string", length: 266, nullable: true)]
    private ?string $ticket_text_footer_2 = null;

    // Getters y Setters

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(string $license): self
    {
        $this->license = $license;
        return $this;
    }

    public function getIdCustomerDefault(): ?int
    {
        return $this->id_customer_default;
    }

    public function setIdCustomerDefault(int $id_customer_default): self
    {
        $this->id_customer_default = $id_customer_default;
        return $this;
    }

    public function getIdAddressDeliveryDefault(): ?int
    {
        return $this->id_address_delivery_default;
    }

    public function setIdAddressDeliveryDefault(int $id_address_delivery_default): self
    {
        $this->id_address_delivery_default = $id_address_delivery_default;
        return $this;
    }

    public function getAllowOutOfStockSales(): bool
    {
        return $this->allow_out_of_stock_sales;
    }

    public function setAllowOutOfStockSales(bool $allow_out_of_stock_sales): self
    {
        $this->allow_out_of_stock_sales = $allow_out_of_stock_sales;
        return $this;
    }

    public function getTicketTextHeader1(): ?string
    {
        return $this->ticket_text_header_1;
    }

    public function setTicketTextHeader1(?string $ticket_text_header_1): self
    {
        $this->ticket_text_header_1 = $ticket_text_header_1;
        return $this;
    }

    public function getTicketTextHeader2(): ?string
    {
        return $this->ticket_text_header_2;
    }

    public function setTicketTextHeader2(?string $ticket_text_header_2): self
    {
        $this->ticket_text_header_2 = $ticket_text_header_2;
        return $this;
    }

    public function getTicketTextFooter1(): ?string
    {
        return $this->ticket_text_footer_1;
    }

    public function setTicketTextFooter1(?string $ticket_text_footer_1): self
    {
        $this->ticket_text_footer_1 = $ticket_text_footer_1;
        return $this;
    }

    public function getTicketTextFooter2(): ?string
    {
        return $this->ticket_text_footer_2;
    }

    public function setTicketTextFooter2(?string $ticket_text_footer_2): self
    {
        $this->ticket_text_footer_2 = $ticket_text_footer_2;
        return $this;
    }
}
