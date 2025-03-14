<?php

namespace App\Entity;
use App\Repository\PsOrdersRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsOrdersRepository::class)]
class PsOrders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_order = null;

    #[ORM\Column(length: 9)]
    private ?string $reference = null;

    #[ORM\Column]
    private ?int $id_shop_group = null;

    #[ORM\Column]
    private ?int $id_shop = null;

    #[ORM\Column]
    private ?int $id_carrier = null;

    #[ORM\Column]
    private ?int $id_lang = null;

    #[ORM\Column]
    private ?int $id_customer = null;

    #[ORM\Column]
    private ?int $id_cart = null;

    #[ORM\Column]
    private ?int $id_currency = null;

    #[ORM\Column]
    private ?int $id_address_delivery = null;

    #[ORM\Column]
    private ?int $id_address_invoice = null;

    #[ORM\Column]
    private ?int $current_state = null;

    #[ORM\Column(length: 32)]
    private ?string $secure_key = null;

    #[ORM\Column(length: 255)]
    private ?string $payment = null;

    #[ORM\Column(length: 255)]
    private ?string $module = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_discounts = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_discounts_tax_incl = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_discounts_tax_excl = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_paid = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_paid_tax_incl = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_paid_tax_excl = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_paid_real = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_products = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_products_wt = null;

    #[ORM\Column]
    private ?int $round_mode = null;

    #[ORM\Column]
    private ?int $round_type = null;

    #[ORM\Column(nullable: true)]
    private ?int $invoice_number = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $invoice_date = null;

    #[ORM\Column]
    private ?bool $valid = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_upd = null;

    private string $origin = 'mayret';

    // Getters y Setters

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function getIdOrder(): ?int
    {
        return $this->id_order;
    }

    public function setIdOrder(?int $id_order): self
    {
        $this->id_order = $id_order;
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function getIdShopGroup(): ?int
    {
        return $this->id_shop_group;
    }

    public function setIdShopGroup(?int $id_shop_group): self
    {
        $this->id_shop_group = $id_shop_group;
        return $this;
    }

    public function getIdShop(): ?int
    {
        return $this->id_shop;
    }

    public function setIdShop(?int $id_shop): self
    {
        $this->id_shop = $id_shop;
        return $this;
    }

    public function getIdCarrier(): ?int
    {
        return $this->id_carrier;
    }

    public function setIdCarrier(?int $id_carrier): self
    {
        $this->id_carrier = $id_carrier;
        return $this;
    }

    public function getIdLang(): ?int
    {
        return $this->id_lang;
    }

    public function setIdLang(?int $id_lang): self
    {
        $this->id_lang = $id_lang;
        return $this;
    }

    public function getIdCustomer(): ?int
    {
        return $this->id_customer;
    }

    public function setIdCustomer(?int $id_customer): self
    {
        $this->id_customer = $id_customer;
        return $this;
    }

    public function getIdCart(): ?int
    {
        return $this->id_cart;
    }

    public function setIdCart(?int $id_cart): self
    {
        $this->id_cart = $id_cart;
        return $this;
    }

    public function getIdCurrency(): ?int
    {
        return $this->id_currency;
    }

    public function setIdCurrency(?int $id_currency): self
    {
        $this->id_currency = $id_currency;
        return $this;
    }

    public function getIdAddressDelivery(): ?int
    {
        return $this->id_address_delivery;
    }

    public function setIdAddressDelivery(?int $id_address_delivery): self
    {
        $this->id_address_delivery = $id_address_delivery;
        return $this;
    }

    public function getIdAddressInvoice(): ?int
    {
        return $this->id_address_invoice;
    }

    public function setIdAddressInvoice(?int $id_address_invoice): self
    {
        $this->id_address_invoice = $id_address_invoice;
        return $this;
    }

    public function getCurrentState(): ?int
    {
        return $this->current_state;
    }

    public function setCurrentState(?int $current_state): self
    {
        $this->current_state = $current_state;
        return $this;
    }

    public function getSecureKey(): ?string
    {
        return $this->secure_key;
    }

    public function setSecureKey(?string $secure_key): self
    {
        $this->secure_key = $secure_key;
        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(?string $payment): self
    {
        $this->payment = $payment;
        return $this;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(?string $module): self
    {
        $this->module = $module;
        return $this;
    }

    public function getTotalDiscounts(): ?float
    {
        return $this->total_discounts;
    }

    public function setTotalDiscounts(?float $total_discounts): self
    {
        $this->total_discounts = $total_discounts;
        return $this;
    }

    public function getTotalDiscountsTaxIncl(): ?float
    {
        return $this->total_discounts_tax_incl;
    }

    public function setTotalDiscountsTaxIncl(?float $total_discounts_tax_incl): self
    {
        $this->total_discounts_tax_incl = $total_discounts_tax_incl;
        return $this;
    }

    public function getTotalDiscountsTaxExcl(): ?float
    {
        return $this->total_discounts_tax_excl;
    }

    public function setTotalDiscountsTaxExcl(?float $total_discounts_tax_excl): self
    {
        $this->total_discounts_tax_excl = $total_discounts_tax_excl;
        return $this;
    }

    public function getTotalPaid(): ?float
    {
        return $this->total_paid;
    }

    public function setTotalPaid(?float $total_paid): self
    {
        $this->total_paid = $total_paid;
        return $this;
    }

    public function getTotalPaidTaxIncl(): ?float
    {
        return $this->total_paid_tax_incl;
    }

    public function setTotalPaidTaxIncl(?float $total_paid_tax_incl): self
    {
        $this->total_paid_tax_incl = $total_paid_tax_incl;
        return $this;
    }

    public function getTotalPaidTaxExcl(): ?float
    {
        return $this->total_paid_tax_excl;
    }

    public function setTotalPaidTaxExcl(?float $total_paid_tax_excl): self
    {
        $this->total_paid_tax_excl = $total_paid_tax_excl;
        return $this;
    }

    public function getTotalPaidReal(): ?float
    {
        return $this->total_paid_real;
    }

    public function setTotalPaidReal(?float $total_paid_real): self
    {
        $this->total_paid_real = $total_paid_real;
        return $this;
    }

    public function getTotalProducts(): ?float
    {
        return $this->total_products;
    }

    public function setTotalProducts(?float $total_products): self
    {
        $this->total_products = $total_products;
        return $this;
    }

    public function getTotalProductsWt(): ?float
    {
        return $this->total_products_wt;
    }

    public function setTotalProductsWt(?float $total_products_wt): self
    {
        $this->total_products_wt = $total_products_wt;
        return $this;
    }

    public function getRoundMode(): ?int
    {
        return $this->round_mode;
    }

    public function setRoundMode(?int $round_mode): self
    {
        $this->round_mode = $round_mode;
        return $this;
    }

    public function getRoundType(): ?int
    {
        return $this->round_type;
    }

    public function setRoundType(?int $round_type): self
    {
        $this->round_type = $round_type;
        return $this;
    }

    public function getInvoiceNumber(): ?int
    {
        return $this->invoice_number;
    }

    public function setInvoiceNumber(?int $invoice_number): self
    {
        $this->invoice_number = $invoice_number;
        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoice_date;
    }

    public function setInvoiceDate(?\DateTimeInterface $invoice_date): self
    {
        $this->invoice_date = $invoice_date;
        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;
        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;
        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(?\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;
        return $this;
    }
}