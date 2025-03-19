<?php

namespace App\EntityFajasMaylu;

use App\RepositoryFajasMaylu\PsStockAvailableFajasMayluRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsStockAvailableFajasMayluRepository::class)]
#[ORM\Table(name: "ps_stock_available", schema: 'fajasmaylu_ps')]
class PsStockAvailable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_stock_available = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product',referencedColumnName:'id_product',nullable: false)]
    private ?PsProduct $id_product = null;

    #[ORM\ManyToOne(targetEntity: PsProductAttribute::class, inversedBy: 'stockAvailable')]
    #[ORM\JoinColumn(name: 'id_product_attribute', referencedColumnName: 'id_product_attribute', nullable: false)]
    private ?PsProductAttribute $id_product_attribute = null;

    #[ORM\ManyToOne(targetEntity:PsShop::class,inversedBy:'stockAvailable')]
    #[ORM\JoinColumn(name:'id_shop',referencedColumnName:'id_shop',nullable: false)]
    private ?PsShop $id_shop = null;


    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $physicalQuantity = null;

    #[ORM\Column]
    private ?int $reservedQuantity = null;

    #[ORM\Column]
    private ?bool $dependsOnStock = null;

    #[ORM\Column]
    private ?bool $outOfStock = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;



    public function getIdStockAvailable(): ?int
    {
        return $this->id_stock_available;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPhysicalQuantity(): ?int
    {
        return $this->physicalQuantity;
    }

    public function setPhysicalQuantity(int $physicalQuantity): static
    {
        $this->physicalQuantity = $physicalQuantity;

        return $this;
    }

    public function getReservedQuantity(): ?int
    {
        return $this->reservedQuantity;
    }

    public function setReservedQuantity(int $reservedQuantity): static
    {
        $this->reservedQuantity = $reservedQuantity;

        return $this;
    }

    public function isDependsOnStock(): ?bool
    {
        return $this->dependsOnStock;
    }

    public function setDependsOnStock(bool $dependsOnStock): static
    {
        $this->dependsOnStock = $dependsOnStock;

        return $this;
    }

    public function isOutOfStock(): ?bool
    {
        return $this->outOfStock;
    }

    public function setOutOfStock(bool $outOfStock): static
    {
        $this->outOfStock = $outOfStock;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
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

    public function getIdProductAttribute(): ?PsProductAttribute
    {
        return $this->id_product_attribute;
    }

    public function setIdProductAttribute(?PsProductAttribute $id_product_attribute): static
    {
        $this->id_product_attribute = $id_product_attribute;

        return $this;
    }
}
