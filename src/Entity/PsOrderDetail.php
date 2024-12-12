<?php

namespace App\Entity;

use App\Repository\PsOrderDetailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\PsOrders;

#[ORM\Entity(repositoryClass: PsOrderDetailRepository::class)]
class PsOrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_order_detail = null;

    #[ORM\ManyToOne(targetEntity: PsOrders::class)]
    #[ORM\JoinColumn(name: "id_order", referencedColumnName: "id_order", nullable: false)]
    private ?PsOrders $idOrder = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_order_invoice = null;

    #[ORM\Column]
    private ?int $id_shop = null;

    #[ORM\Column]
    private ?int $product_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $product_attribute_id = null;

    #[ORM\Column(length: 255)]
    private ?string $product_name = null;

    #[ORM\Column]
    private ?int $product_quantity = null;

    #[ORM\Column]
    private ?int $product_quantity_in_stock = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $product_price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $reduction_percent = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $reduction_amount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $reduction_amount_tax_incl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $reduction_amount_tax_excl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $group_reduction = null;

    #[ORM\Column]
    private ?int $product_quantity_discount = null;

    #[ORM\Column(length: 13, nullable: true)]
    private ?string $product_ean13 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $product_reference = null;

    #[ORM\Column]
    private ?int $id_tax_rules_group = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tax_name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?float $tax_rate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $total_price_tax_incl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $total_price_tax_excl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $unit_price_tax_incl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $unit_price_tax_excl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?float $original_product_price = null;

    // Getters y Setters

    public function getIdOrderDetail(): ?int
    {
        return $this->id_order_detail;
    }

    public function setIdOrderDetail(int $id_order_detail): static
    {
        $this->id_order_detail = $id_order_detail;

        return $this;
    }

    public function getOrder(): ?PsOrders // Corrección aquí
    {
        return $this->idOrder;
    }

    public function setOrder(PsOrders $idOrder): static
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    public function getIdOrderInvoice(): ?int
    {
        return $this->id_order_invoice;
    }

    public function setIdOrderInvoice(?int $id_order_invoice): static
    {
        $this->id_order_invoice = $id_order_invoice;

        return $this;
    }

    public function getIdShop(): ?int
    {
        return $this->id_shop;
    }

    public function setIdShop(int $id_shop): static
    {
        $this->id_shop = $id_shop;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getProductAttributeId(): ?int
    {
        return $this->product_attribute_id;
    }

    public function setProductAttributeId(?int $product_attribute_id): static
    {
        $this->product_attribute_id = $product_attribute_id;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): static
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getProductQuantity(): ?int
    {
        return $this->product_quantity;
    }

    public function setProductQuantity(int $product_quantity): static
    {
        $this->product_quantity = $product_quantity;

        return $this;
    }

    public function getProductQuantityInStock(): ?int
    {
        return $this->product_quantity_in_stock;
    }

    public function setProductQuantityInStock(int $product_quantity_in_stock): static
    {
        $this->product_quantity_in_stock = $product_quantity_in_stock;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->product_price;
    }

    public function setProductPrice(float $product_price): static
    {
        $this->product_price = $product_price;

        return $this;
    }

    public function getReductionPercent(): ?float
    {
        return $this->reduction_percent;
    }

    public function setReductionPercent(?float $reduction_percent): static
    {
        $this->reduction_percent = $reduction_percent;

        return $this;
    }

    public function getReductionAmount(): ?float
    {
        return $this->reduction_amount;
    }

    public function setReductionAmount(?float $reduction_amount): static
    {
        $this->reduction_amount = $reduction_amount;

        return $this;
    }

    public function getReductionAmountTaxIncl(): ?float
    {
        return $this->reduction_amount_tax_incl;
    }

    public function setReductionAmountTaxIncl(?float $reduction_amount_tax_incl): static
    {
        $this->reduction_amount_tax_incl = $reduction_amount_tax_incl;

        return $this;
    }

    public function getReductionAmountTaxExcl(): ?float
    {
        return $this->reduction_amount_tax_excl;
    }

    public function setReductionAmountTaxExcl(?float $reduction_amount_tax_excl): static
    {
        $this->reduction_amount_tax_excl = $reduction_amount_tax_excl;

        return $this;
    }

    public function getGroupReduction(): ?float
    {
        return $this->group_reduction;
    }

    public function setGroupReduction(?float $group_reduction): static
    {
        $this->group_reduction = $group_reduction;

        return $this;
    }

    public function getProductQuantityDiscount(): ?int
    {
        return $this->product_quantity_discount;
    }

    public function setProductQuantityDiscount(int $product_quantity_discount): static
    {
        $this->product_quantity_discount = $product_quantity_discount;

        return $this;
    }

    public function getProductEan13(): ?string
    {
        return $this->product_ean13;
    }

    public function setProductEan13(?string $product_ean13): static
    {
        $this->product_ean13 = $product_ean13;

        return $this;
    }

    public function getProductReference(): ?string
    {
        return $this->product_reference;
    }

    public function setProductReference(?string $product_reference): static
    {
        $this->product_reference = $product_reference;

        return $this;
    }

    public function getIdTaxRulesGroup(): ?int
    {
        return $this->id_tax_rules_group;
    }

    public function setIdTaxRulesGroup(int $id_tax_rules_group): static
    {
        $this->id_tax_rules_group = $id_tax_rules_group;

        return $this;
    }

    public function getTaxName(): ?string
    {
        return $this->tax_name;
    }

    public function setTaxName(?string $tax_name): static
    {
        $this->tax_name = $tax_name;

        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->tax_rate;
    }

    public function setTaxRate(float $tax_rate): static
    {
        $this->tax_rate = $tax_rate;

        return $this;
    }

    public function getTotalPriceTaxIncl(): ?float
    {
        return $this->total_price_tax_incl;
    }

    public function setTotalPriceTaxIncl(float $total_price_tax_incl): static
    {
        $this->total_price_tax_incl = $total_price_tax_incl;

        return $this;
    }

    public function getTotalPriceTaxExcl(): ?float
    {
        return $this->total_price_tax_excl;
    }

    public function setTotalPriceTaxExcl(float $total_price_tax_excl): static
    {
        $this->total_price_tax_excl = $total_price_tax_excl;

        return $this;
    }

    public function getUnitPriceTaxIncl(): ?float
    {
        return $this->unit_price_tax_incl;
    }

    public function setUnitPriceTaxIncl(float $unit_price_tax_incl): static
    {
        $this->unit_price_tax_incl = $unit_price_tax_incl;

        return $this;
    }

    public function getUnitPriceTaxExcl(): ?float
    {
        return $this->unit_price_tax_excl;
    }

    public function setUnitPriceTaxExcl(float $unit_price_tax_excl): static
    {
        $this->unit_price_tax_excl = $unit_price_tax_excl;

        return $this;
    }

    public function getOriginalProductPrice(): ?float
    {
        return $this->original_product_price;
    }

    public function setOriginalProductPrice(float $original_product_price): static
    {
        $this->original_product_price = $original_product_price;

        return $this;
    }
}
