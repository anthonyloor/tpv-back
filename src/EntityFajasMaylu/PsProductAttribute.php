<?php

namespace App\EntityFajasMaylu;

use App\RepositoryFajasMaylu\PsProductAttributeFajasMayluRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsProductAttributeFajasMayluRepository::class)]
#[ORM\Table(name: "ps_product_attribute", schema: 'fajasmaylu_ps')]
class PsProductAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_product_attribute = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product',nullable: false)]
    private ?PsProduct $idProduct = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $supplierReference = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $ean13 = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $upc = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 4)]
    private ?string $wholesalePrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 6)]
    private ?string $ecotax = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $weight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6)]
    private ?string $unitPriceImpact = null;

    #[ORM\Column]
    private ?int $minimalQuantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $lowStockThreshold = null;

    #[ORM\Column]
    private ?bool $lowStockAlert = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $availableDate = null;

    public function getIdProductAttribute(): ?int
    {
        return $this->id_product_attribute;
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSupplierReference(): ?string
    {
        return $this->supplierReference;
    }

    public function setSupplierReference(?string $supplier_reference): static
    {
        $this->supplier_reference = $supplier_reference;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getEan13(): ?string
    {
        return $this->ean13;
    }

    public function setEan13(?string $ean13): static
    {
        $this->ean13 = $ean13;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getUpc(): ?string
    {
        return $this->upc;
    }

    public function setUpc(?string $upc): static
    {
        $this->upc = $upc;

        return $this;
    }

    public function getWholesalePrice(): ?string
    {
        return $this->wholesalePrice;
    }

    public function setWholesalePrice(string $wholesalePrice): static
    {
        $this->wholesalePrice = $wholesalePrice;

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

    public function getEcotax(): ?string
    {
        return $this->ecotax;
    }

    public function setEcotax(string $ecotax): static
    {
        $this->ecotax = $ecotax;

        return $this;
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

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getUnitPriceImpact(): ?string
    {
        return $this->unitPriceImpact;
    }

    public function setUnitPriceImpact(string $unitPriceImpact): static
    {
        $this->unitPriceImpact = $unitPriceImpact;

        return $this;
    }

    public function getMinimalQuantity(): ?int
    {
        return $this->minimalQuantity;
    }

    public function setMinimalQuantity(int $minimalQuantity): static
    {
        $this->minimalQuantity = $minimalQuantity;

        return $this;
    }

    public function getLowStockThreshold(): ?int
    {
        return $this->lowStockThreshold;
    }

    public function setLowStockThreshold(?int $lowStockThreshold): static
    {
        $this->lowStockThreshold = $lowStockThreshold;

        return $this;
    }

    public function isLowStockAlert(): ?bool
    {
        return $this->lowStockAlert;
    }

    public function setLowStockAlert(bool $lowStockAlert): static
    {
        $this->lowStockAlert = $lowStockAlert;

        return $this;
    }

    public function getAvailableDate(): ?\DateTimeInterface
    {
        return $this->availableDate;
    }

    public function setAvailableDate(?\DateTimeInterface $availableDate): static
    {
        $this->availableDate = $availableDate;

        return $this;
    }
}
