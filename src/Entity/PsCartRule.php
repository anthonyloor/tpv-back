<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
class PsCartRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_cart_rule = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_customer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_from = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_to = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $quantity_per_user = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $priority = null;

    #[ORM\Column(type: "string", length: 254, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $partial_use = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2, nullable: true)]
    private ?float $minimum_amount = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $minimum_amount_tax = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $minimum_amount_currency = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $minimum_amount_shipping = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $country_restriction = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $carrier_restriction = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $group_restriction = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $cart_rule_restriction = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $product_restriction = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $shop_restriction = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $free_shipping = null;

    #[ORM\Column(type: "decimal", precision: 5, scale: 2, nullable: true)]
    private ?float $reduction_percent = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2, nullable: true)]
    private ?float $reduction_amount = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $reduction_tax = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $reduction_currency = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $reduction_product = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $reduction_exclude_special = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $gift_product = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $gift_product_attribute = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $highlight = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_upd = null;

    // Getters y Setters...
    public function getIdCartRule(): ?int
    {
        return $this->id_cart_rule;
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

    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->date_from;
    }

    public function setDateFrom(?\DateTimeInterface $date_from): self
    {
        $this->date_from = $date_from;
        return $this;
    }

    public function getDateTo(): ?\DateTimeInterface
    {
        return $this->date_to;
    }

    public function setDateTo(?\DateTimeInterface $date_to): self
    {
        $this->date_to = $date_to;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getQuantityPerUser(): ?int
    {
        return $this->quantity_per_user;
    }

    public function setQuantityPerUser(?int $quantity_per_user): self
    {
        $this->quantity_per_user = $quantity_per_user;
        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getPartialUse(): ?bool
    {
        return $this->partial_use;
    }

    public function setPartialUse(?bool $partial_use): self
    {
        $this->partial_use = $partial_use;
        return $this;
    }

    public function getMinimumAmount(): ?float
    {
        return $this->minimum_amount;
    }

    public function setMinimumAmount(?float $minimum_amount): self
    {
        $this->minimum_amount = $minimum_amount;
        return $this;
    }

    public function getMinimumAmountTax(): ?bool
    {
        return $this->minimum_amount_tax;
    }

    public function setMinimumAmountTax(?bool $minimum_amount_tax): self
    {
        $this->minimum_amount_tax = $minimum_amount_tax;
        return $this;
    }

    public function getMinimumAmountCurrency(): ?int
    {
        return $this->minimum_amount_currency;
    }

    public function setMinimumAmountCurrency(?int $minimum_amount_currency): self
    {
        $this->minimum_amount_currency = $minimum_amount_currency;
        return $this;
    }

    public function getMinimumAmountShipping(): ?bool
    {
        return $this->minimum_amount_shipping;
    }

    public function setMinimumAmountShipping(?bool $minimum_amount_shipping): self
    {
        $this->minimum_amount_shipping = $minimum_amount_shipping;
        return $this;
    }

    public function getCountryRestriction(): ?bool
    {
        return $this->country_restriction;
    }

    public function setCountryRestriction(?bool $country_restriction): self
    {
        $this->country_restriction = $country_restriction;
        return $this;
    }

    public function getCarrierRestriction(): ?bool
    {
        return $this->carrier_restriction;
    }

    public function setCarrierRestriction(?bool $carrier_restriction): self
    {
        $this->carrier_restriction = $carrier_restriction;
        return $this;
    }

    public function getGroupRestriction(): ?bool
    {
        return $this->group_restriction;
    }

    public function setGroupRestriction(?bool $group_restriction): self
    {
        $this->group_restriction = $group_restriction;
        return $this;
    }

    public function getCartRuleRestriction(): ?bool
    {
        return $this->cart_rule_restriction;
    }

    public function setCartRuleRestriction(?bool $cart_rule_restriction): self
    {
        $this->cart_rule_restriction = $cart_rule_restriction;
        return $this;
    }

    public function getProductRestriction(): ?bool
    {
        return $this->product_restriction;
    }

    public function setProductRestriction(?bool $product_restriction): self
    {
        $this->product_restriction = $product_restriction;
        return $this;
    }

    public function getShopRestriction(): ?bool
    {
        return $this->shop_restriction;
    }

    public function setShopRestriction(?bool $shop_restriction): self
    {
        $this->shop_restriction = $shop_restriction;
        return $this;
    }

    public function getFreeShipping(): ?bool
    {
        return $this->free_shipping;
    }

    public function setFreeShipping(?bool $free_shipping): self
    {
        $this->free_shipping = $free_shipping;
        return $this;
    }

    public function getReductionPercent(): ?float
    {
        return $this->reduction_percent;
    }

    public function setReductionPercent(?float $reduction_percent): self
    {
        $this->reduction_percent = $reduction_percent;
        return $this;
    }

    public function getReductionAmount(): ?float
    {
        return $this->reduction_amount;
    }

    public function setReductionAmount(?float $reduction_amount): self
    {
        $this->reduction_amount = $reduction_amount;
        return $this;
    }

    public function getReductionTax(): ?bool
    {
        return $this->reduction_tax;
    }

    public function setReductionTax(?bool $reduction_tax): self
    {
        $this->reduction_tax = $reduction_tax;
        return $this;
    }

    public function getReductionCurrency(): ?int
    {
        return $this->reduction_currency;
    }

    public function setReductionCurrency(?int $reduction_currency): self
    {
        $this->reduction_currency = $reduction_currency;
        return $this;
    }

    public function getReductionProduct(): ?int
    {
        return $this->reduction_product;
    }

    public function setReductionProduct(?int $reduction_product): self
    {
        $this->reduction_product = $reduction_product;
        return $this;
    }

    public function getReductionExcludeSpecial(): ?bool
    {
        return $this->reduction_exclude_special;
    }

    public function setReductionExcludeSpecial(?bool $reduction_exclude_special): self
    {
        $this->reduction_exclude_special = $reduction_exclude_special;
        return $this;
    }

    public function getGiftProduct(): ?int
    {
        return $this->gift_product;
    }

    public function setGiftProduct(?int $gift_product): self
    {
        $this->gift_product = $gift_product;
        return $this;
    }

    public function getGiftProductAttribute(): ?int
    {
        return $this->gift_product_attribute;
    }

    public function setGiftProductAttribute(?int $gift_product_attribute): self
    {
        $this->gift_product_attribute = $gift_product_attribute;
        return $this;
    }

    public function getHighlight(): ?bool
    {
        return $this->highlight;
    }

    public function setHighlight(?bool $highlight): self
    {
        $this->highlight = $highlight;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;
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
