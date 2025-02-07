<?php

namespace App\Entity;
use App\Repository\LpControlStockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpControlStockRepository::class)]
class LpControlStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_control_stock  = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_product = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_product_atributte = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_stock_available = null;  

    #[ORM\Column(type: "integer")]
    private ?int $id_shop = null;

    //getters and setters
    public function getIdControlStock(): ?int
    {
        return $this->id_control_stock;
    }

    public function setIdControlStock(?int $id_control_stock): self
    {
        $this->id_control_stock = $id_control_stock;
        return $this;
    }

    public function getIdProduct(): ?int
    {
        return $this->id_product;
    }

    public function setIdProduct(?int $id_product): self
    {
        $this->id_product = $id_product;
        return $this;
    }

    public function getIdProductAtributte(): ?int
    {
        return $this->id_product_atributte;
    }

    public function setIdProductAtributte(?int $id_product_atributte): self
    {
        $this->id_product_atributte = $id_product_atributte;
        return $this;
    }

    public function getIdStockAvailable(): ?int
    {
        return $this->id_stock_available;
    }

    public function setIdStockAvailable(?int $id_stock_available): self
    {
        $this->id_stock_available = $id_stock_available;
        return $this;
    }

    public function getIdShop(): ?int
    {
        return $this->id_shop;
    }

    public function setIdShop(?int $id_shop): self
    {
        $this->id_shop = $id_shop;
        return $this;
    }
}
