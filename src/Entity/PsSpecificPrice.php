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
    private ?int $idSpecificPrice = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product',nullable: false)]
    private ?PsProduct $idProduct = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PsShop $idShop = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PsShopGroup $idShopGroup = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product_attribute',nullable: false)]
    private ?PsProductAttribute $idProductAttribute = null;

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
    private ?PsGroup $idGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $reductionType = null;

    public function getIdSpecificPrice(): ?int
    {
        return $this->idSpecificPrice;
    }

    public function getIdProduct(): ?PsProduct
    {
        return $this->idProduct;
    }

    public function setIdProduct(?PsProduct $idProduct): static
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getIdShop(): ?PsShop
    {
        return $this->idShop;
    }

    public function setIdShop(?PsShop $idShop): static
    {
        $this->idShop = $idShop;

        return $this;
    }

    public function getIdShopGroup(): ?PsShopGroup
    {
        return $this->idShopGroup;
    }

    public function setIdShopGroup(?PsShopGroup $idShopGroup): static
    {
        $this->idShopGroup = $idShopGroup;

        return $this;
    }

    public function getIdProductAttribute(): ?PsProductAttribute
    {
        return $this->idProductAttribute;
    }

    public function setIdProductAttribute(?PsProductAttribute $idProductAttribute): static
    {
        $this->idProductAttribute = $idProductAttribute;

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
        return $this->idGroup;
    }

    public function setIdGroup(?PsGroup $idGroup): static
    {
        $this->idGroup = $idGroup;

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
}
