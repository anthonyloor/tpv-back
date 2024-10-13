<?php

namespace App\Entity;

use App\Repository\PsShopGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsShopGroupRepository::class)]
class PsShopGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id_shop_group')]
    private ?int $idShopGroup = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $shareCustomer = null;

    #[ORM\Column]
    private ?bool $shareOrder = null;

    #[ORM\Column]
    private ?bool $shareStock = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $deleted = null;

    public function getIdShopGroup(): ?int
    {
        return $this->idShopGroup;
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

    public function isShareCustomer(): ?bool
    {
        return $this->shareCustomer;
    }

    public function setShareCustomer(bool $shareCustomer): static
    {
        $this->shareCustomer = $shareCustomer;

        return $this;
    }

    public function isShareOrder(): ?bool
    {
        return $this->shareOrder;
    }

    public function setShareOrder(bool $shareOrder): static
    {
        $this->shareOrder = $shareOrder;

        return $this;
    }

    public function isShareStock(): ?bool
    {
        return $this->shareStock;
    }

    public function setShareStock(bool $shareStock): static
    {
        $this->shareStock = $shareStock;

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
