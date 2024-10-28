<?php

namespace App\Entity;

use App\Repository\PsSpecificPriceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsSpecificPriceRepository::class)]
class PsSpecificPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_specific_price = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product',nullable: false)]
    private ?PsProduct $id_product = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PsShop $id_shop = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PsShopGroup $id_shop_group = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product_attribute',nullable: false)]
    private ?PsProductAttribute $id_product_attribute = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $fromQuantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $reduction = null;

    #[ORM\Column]
    private ?bool $reductionTax = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_group',nullable: false)]
    private ?PsGroup $id_group = null;

    #[ORM\Column(length: 255)]
    private ?string $reductionType = null;

    #[ORM\Column]
    private ?int $id_customer = null;

    public function getIdSpecificPrice(): ?int
    {
        return $this->id_specific_price;
    }

    public function getIdProduct(): ?PsProduct
    {
        return $this->id_product;
    }

    public function setIdProduct(?PsProduct $id_product): static
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getIdShop(): ?PsShop
    {
        return $this->id_shop;
    }

    public function setIdShop(?PsShop $id_shop): static
    {
        $this->id_shop = $id_shop;

        return $this;
    }

    public function getIdShopGroup(): ?PsShopGroup
    {
        return $this->id_shop_group;
    }

    public function setIdShopGroup(?PsShopGroup $id_shop_group): static
    {
        $this->id_shop_group = $id_shop_group;

        return $this;
    }

    public function getIdProductAttribute(): ?PsProductAttribute
    {
        return $this->id_product_attribute;
    }

    public function setIdProductAttribute(?PsProductAttribute $id_product_attribute): static
    {
        $this->id_product_attribute = $id_product_attribute;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getFromQuantity(): ?int
    {
        return $this->fromQuantity;
    }

    public function setFromQuantity(int $fromQuantity): static
    {
        $this->fromQuantity = $fromQuantity;

        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(string $reduction): static
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function isReductionTax(): ?bool
    {
        return $this->reductionTax;
    }

    public function setReductionTax(bool $reductionTax): static
    {
        $this->reductionTax = $reductionTax;

        return $this;
    }

    public function getIdGroup(): ?PsGroup
    {
        return $this->id_group;
    }

    public function setIdGroup(?PsGroup $id_group): static
    {
        $this->id_group = $id_group;

        return $this;
    }

    public function getReductionType(): ?string
    {
        return $this->reductionType;
    }

    public function setReductionType(string $reductionType): static
    {
        $this->reductionType = $reductionType;

        return $this;
    }

    public function getIdCustomer(): ?int
    {
        return $this->id_customer;
    }

    public function setIdCustomer(int $id_customer): static
    {
        $this->id_customer = $id_customer;

        return $this;
    }
}
