<?php

namespace App\Entity;

use App\Repository\LpStockFixedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpStockFixedRepository::class)]
class LpStockFixed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_stock = null;

    #[ORM\Column(type: 'string', length: 13)]
    private ?string $ean13 = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity_shop_1 = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity_shop_2 = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity_shop_3 = null;

    public function getIdStock(): ?int
    {
        return $this->id_stock;
    }

    public function getEan13(): ?string
    {
        return $this->ean13;
    }

    public function setEan13(string $ean13): static
    {
        $this->ean13 = $ean13;
        return $this;
    }

    public function getQuantityShop1(): ?int
    {
        return $this->quantity_shop_1;
    }

    public function setQuantityShop1(int $quantity_shop_1): static
    {
        $this->quantity_shop_1 = $quantity_shop_1;
        return $this;
    }

    public function getQuantityShop2(): ?int
    {
        return $this->quantity_shop_2;
    }

    public function setQuantityShop2(int $quantity_shop_2): static
    {
        $this->quantity_shop_2 = $quantity_shop_2;
        return $this;
    }

    public function getQuantityShop3(): ?int
    {
        return $this->quantity_shop_3;
    }

    public function setQuantityShop3(int $quantity_shop_3): static
    {
        $this->quantity_shop_3 = $quantity_shop_3;
        return $this;
    }
}
