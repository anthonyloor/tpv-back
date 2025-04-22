<?php

namespace App\Entity;

use App\Repository\LpWarehouseMovementDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpWarehouseMovementDetailRepository::class)]
class LpWarehouseMovementDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_warehouse_movement_detail = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_warehouse_movement = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $sent_quantity = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $recived_quantity = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_product = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_product_attribute = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $product_name = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $status = null;

    #[ORM\Column(type: "string", length: 13)]
    private ?string $ean13 = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_control_stock = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $stock_origin = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $stock_destiny = null;


    // Getters y Setters
    public function getIdControlStock(): ?int
    {
        return $this->id_control_stock;
    }

    public function setIdControlStock(?int $id_control_stock): self
    {
        $this->id_control_stock = $id_control_stock;
        return $this;
    }

    public function getEan13(): ?string
    {
        return $this->ean13;
    }

    public function setEan13(string $ean13): self
    {
        $this->ean13 = $ean13;
        return $this;
    }

    public function getIdWarehouseMovementDetail(): ?int
    {
        return $this->id_warehouse_movement_detail;
    }

    public function getIdWarehouseMovement(): ?int
    {
        return $this->id_warehouse_movement;
    }

    public function setIdWarehouseMovement(int $id_warehouse_movement): self
    {
        $this->id_warehouse_movement = $id_warehouse_movement;
        return $this;
    }

    public function getSentQuantity(): ?int
    {
        return $this->sent_quantity;
    }

    public function setSentQuantity(?int $sent_quantity): self
    {
        $this->sent_quantity = $sent_quantity;
        return $this;
    }

    public function getRecivedQuantity(): ?int
    {
        return $this->recived_quantity;
    }

    public function setRecivedQuantity(?int $recived_quantity): self
    {
        $this->recived_quantity = $recived_quantity;
        return $this;
    }

    public function getIdProduct(): ?int
    {
        return $this->id_product;
    }

    public function setIdProduct(int $id_product): self
    {
        $this->id_product = $id_product;
        return $this;
    }

    public function getIdProductAttribute(): ?int
    {
        return $this->id_product_attribute;
    }

    public function setIdProductAttribute(int $id_product_attribute): self
    {
        $this->id_product_attribute = $id_product_attribute;
        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getStockOrigin(): ?int
    {
        return $this->stock_origin;
    }
    public function setStockOrigin(?int $stock_origin): self
    {
        $this->stock_origin = $stock_origin;
        return $this;
    }
    public function getStockDestiny(): ?int
    {
        return $this->stock_destiny;
    }
    public function setStockDestiny(?int $stock_destiny): self
    {
        $this->stock_destiny = $stock_destiny;
        return $this;
    }
}
