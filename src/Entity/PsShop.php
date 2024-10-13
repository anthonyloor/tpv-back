<?php

namespace App\Entity;

use App\Repository\PsShopRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsShopRepository::class)]
class PsShop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_shop = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_shop_group',nullable: false)]
    private ?PsShopGroup $idShopGroup = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $themeName = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $deleted = null;

    public function getIdShop(): ?int
    {
        return $this->id_shop;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getThemeName(): ?string
    {
        return $this->themeName;
    }

    public function setThemeName(string $themeName): static
    {
        $this->themeName = $themeName;

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

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }
}
