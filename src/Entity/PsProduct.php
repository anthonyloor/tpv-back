<?php

namespace App\Entity;

use App\Repository\PsProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PsProductRepository::class)]
class PsProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idProduct = null;

    #[ORM\Column]
    private ?int $idSupplier = null;

    #[ORM\Column]
    private ?int $idManufacturer = null;

    #[ORM\Column]
    private ?int $idCategoryDefault = null;

    #[ORM\Column]
    private ?int $idShopDefault = null;

    #[ORM\Column]
    private ?int $idTaxRulesGroup = null;

    #[ORM\Column]
    private ?bool $onSale = null;

    #[ORM\Column]
    private ?bool $onlineOnly = null;

    #[ORM\Column(length: 13, nullable: true),Assert\NotBlank(message:'El ean13 es obligatorio')]
    private ?string $ean13 = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $upc = null;

    #[ORM\Column]
    private ?float $ecotax = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $minimalQuantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $lowStockThreshold = null;

    #[ORM\Column]
    private ?bool $lowStockAlert = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $wholesalePrice = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unity = null;

    #[ORM\Column]
    private ?float $unitPriceRatio = null;

    #[ORM\Column]
    private ?float $additionalShippingCost = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $supplierReference = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $location = null;

    #[ORM\Column]
    private ?float $width = null;

    #[ORM\Column]
    private ?float $height = null;

    #[ORM\Column]
    private ?float $depth = null;

    #[ORM\Column]
    private ?float $weight = null;

    #[ORM\Column]
    private ?int $outOfStock = null;

    #[ORM\Column]
    private ?bool $additionalDeliveryTimes = null;

    #[ORM\Column]
    private ?bool $quantityDiscount = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $customizable = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $uploadableFiles = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $textFields = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function setIdProduct(int $idProduct): static
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getIdSupplier(): ?int
    {
        return $this->idSupplier;
    }

    public function setIdSupplier(int $idSupplier): static
    {
        $this->idSupplier = $idSupplier;

        return $this;
    }

    public function getIdManufacturer(): ?int
    {
        return $this->idManufacturer;
    }

    public function setIdManufacturer(int $idManufacturer): static
    {
        $this->idManufacturer = $idManufacturer;

        return $this;
    }

    public function getIdCategoryDefault(): ?int
    {
        return $this->idCategoryDefault;
    }

    public function setIdCategoryDefault(int $idCategoryDefault): static
    {
        $this->idCategoryDefault = $idCategoryDefault;

        return $this;
    }

    public function getIdShopDefault(): ?int
    {
        return $this->idShopDefault;
    }

    public function setIdShopDefault(int $idShopDefault): static
    {
        $this->idShopDefault = $idShopDefault;

        return $this;
    }

    public function getIdTaxRulesGroup(): ?int
    {
        return $this->idTaxRulesGroup;
    }

    public function setIdTaxRulesGroup(int $idTaxRulesGroup): static
    {
        $this->idTaxRulesGroup = $idTaxRulesGroup;

        return $this;
    }

    public function isOnSale(): ?bool
    {
        return $this->onSale;
    }

    public function setOnSale(bool $onSale): static
    {
        $this->onSale = $onSale;

        return $this;
    }

    public function isOnlineOnly(): ?bool
    {
        return $this->onlineOnly;
    }

    public function setOnlineOnly(bool $onlineOnly): static
    {
        $this->onlineOnly = $onlineOnly;

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

    public function getEcotax(): ?float
    {
        return $this->ecotax;
    }

    public function setEcotax(float $ecotax): static
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getWholesalePrice(): ?float
    {
        return $this->wholesalePrice;
    }

    public function setWholesalePrice(float $wholesalePrice): static
    {
        $this->wholesalePrice = $wholesalePrice;

        return $this;
    }

    public function getUnity(): ?string
    {
        return $this->unity;
    }

    public function setUnity(?string $unity): static
    {
        $this->unity = $unity;

        return $this;
    }

    public function getUnitPriceRatio(): ?float
    {
        return $this->unitPriceRatio;
    }

    public function setUnitPriceRatio(float $unitPriceRatio): static
    {
        $this->unitPriceRatio = $unitPriceRatio;

        return $this;
    }

    public function getAdditionalShippingCost(): ?float
    {
        return $this->additionalShippingCost;
    }

    public function setAdditionalShippingCost(float $additionalShippingCost): static
    {
        $this->additionalShippingCost = $additionalShippingCost;

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

    public function setSupplierReference(?string $supplierReference): static
    {
        $this->supplierReference = $supplierReference;

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

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getDepth(): ?float
    {
        return $this->depth;
    }

    public function setDepth(float $depth): static
    {
        $this->depth = $depth;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getOutOfStock(): ?int
    {
        return $this->outOfStock;
    }

    public function setOutOfStock(int $outOfStock): static
    {
        $this->outOfStock = $outOfStock;

        return $this;
    }

    public function isAdditionalDeliveryTimes(): ?bool
    {
        return $this->additionalDeliveryTimes;
    }

    public function setAdditionalDeliveryTimes(bool $additionalDeliveryTimes): static
    {
        $this->additionalDeliveryTimes = $additionalDeliveryTimes;

        return $this;
    }

    public function isQuantityDiscount(): ?bool
    {
        return $this->quantityDiscount;
    }

    public function setQuantityDiscount(bool $quantityDiscount): static
    {
        $this->quantityDiscount = $quantityDiscount;

        return $this;
    }

    public function getCustomizable(): ?int
    {
        return $this->customizable;
    }

    public function setCustomizable(int $customizable): static
    {
        $this->customizable = $customizable;

        return $this;
    }

    public function getUploadableFiles(): ?int
    {
        return $this->uploadableFiles;
    }

    public function setUploadableFiles(int $uploadableFiles): static
    {
        $this->uploadableFiles = $uploadableFiles;

        return $this;
    }

    public function getTextFields(): ?int
    {
        return $this->textFields;
    }

    public function setTextFields(int $textFields): static
    {
        $this->textFields = $textFields;

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
}
