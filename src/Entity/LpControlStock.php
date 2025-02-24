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
    private ?int $id_product_attribute = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_shop = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_upd = null;

    #[ORM\Column(type: "string", length: 13)]
    private ?string $ean13 = null;

    #[ORM\Column(type: "boolean")]
    private ?bool $active = null;

    //getters and setters

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;
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

    public function getEan13(): ?string
    {
        return $this->ean13;
    }

    public function setEan13(?string $ean13): self
    {
        $this->ean13 = $ean13;
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
        return $this->id_product_attribute;
    }

    public function setIdProductAtributte(?int $id_product_attribute): self
    {
        $this->id_product_attribute = $id_product_attribute;
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
