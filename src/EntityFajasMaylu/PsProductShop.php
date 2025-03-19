<?php

namespace App\EntityFajasMaylu;

use App\RepositoryFajasMaylu\PsProductShopFajasMayluRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsProductShopFajasMayluRepository::class)]
#[ORM\Table(name: "ps_product_shop", schema: 'fajasmaylu_ps')]

class PsProductShop
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id_product = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $id_shop = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $id_category_default  = null;

    #[ORM\Column]
    private ?int $id_tax_rules_group = null;

    #[ORM\Column]
    private ?bool $on_sale = null;

    #[ORM\Column]
    private ?bool $low_stock_alert = null;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 6)]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $show_price = null;

    #[ORM\Column(length: 32)]
    private ?string $visibility = null;

    #[ORM\Column]
    private ?int $cache_default_attribute = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date_upd = null;

    #[ORM\Column(type: 'datetime')]

    public function getIdCategoryDefault(): ?int
    {
        return $this->id_category_default;
    }

    public function getIdProduct(): ?int
    {
        return $this->id_product;
    }

    public function getIdShop(): ?int
    {
        return $this->id_shop;
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

    public function isOnSale(): ?bool
    {
        return $this->on_sale;
    }

    public function setOnSale(bool $on_sale): static
    {
        $this->on_sale = $on_sale;
        return $this;
    }

    public function isLowStockAlert(): ?bool
    {
        return $this->low_stock_alert;
    }

    public function setLowStockAlert(bool $low_stock_alert): static
    {
        $this->low_stock_alert = $low_stock_alert;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function isShowPrice(): ?bool
    {
        return $this->show_price;
    }

    public function setShowPrice(bool $show_price): static
    {
        $this->show_price = $show_price;
        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): static
    {
        $this->visibility = $visibility;
        return $this;
    }

    public function getCacheDefaultAttribute(): ?int
    {
        return $this->cache_default_attribute;
    }

    public function setCacheDefaultAttribute(int $cache_default_attribute): static
    {
        $this->cache_default_attribute = $cache_default_attribute;
        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): static
    {
        $this->date_add = $date_add;
        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(\DateTimeInterface $date_upd): static
    {
        $this->date_upd = $date_upd;
        return $this;
    }
}
