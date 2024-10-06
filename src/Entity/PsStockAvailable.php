<?php

namespace App\Entity;

use App\Repository\PsStockAvailableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsStockAvailableRepository::class)]
class PsStockAvailable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idStockAvailable = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product',referencedColumnName:'id_product',nullable: false)]
    private ?PsProduct $idProduct = null;

    #[ORM\ManyToOne(targetEntity: PsProductAttribute::class, inversedBy: 'stockAvailable')]
    #[ORM\JoinColumn(name: 'id_product_attribute', referencedColumnName: 'id_product_attribute', nullable: false)]
    private ?PsProductAttribute $idProductAttribute = null;

    #[ORM\ManyToOne(targetEntity:PsShop::class,inversedBy:'stockAvailable')]
    #[ORM\JoinColumn(name:'id_shop',referencedColumnName:'id_shop',nullable: false)]
    private ?PsShop $idShop = null;


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



    #[ORM\Column(length: 255)]
    private ?string $no = null;


    public function getIdStockAvailable(): ?int
    {
        return $this->idStockAvailable;
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
        return $this->idProduct;
    }

    public function setIdProduct(?PsProduct $idProduct): static
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getNo(): ?string
    {
        return $this->no;
    }

    public function setNo(string $no): static
    {
        $this->no = $no;

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

    public function getIdProductAttribute(): ?PsProductAttribute
    {
        return $this->idProductAttribute;
    }

    public function setIdProductAttribute(?PsProductAttribute $idProductAttribute): static
    {
        $this->idProductAttribute = $idProductAttribute;

        return $this;
    }
}
