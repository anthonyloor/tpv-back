<?php

namespace App\Entity;

use App\Repository\PsCategoryProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsCategoryProductRepository::class)]
#[ORM\Table(name: "ps_category_product")]
class PsCategoryProduct
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: PsCategory::class)]
    #[ORM\JoinColumn(name: "id_category", referencedColumnName: "id_category", nullable: false)]
    private ?PsCategory $id_category = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: PsProduct::class)]
    #[ORM\JoinColumn(name: "id_product", referencedColumnName: "id_product", nullable: false)]
    private ?PsProduct $id_product = null;

    #[ORM\Column(type: "integer")]
    private ?int $position = null;

    // Getters y Setters

    public function getIdCategory(): ?PsCategory
    {
        return $this->id_category;
    }

    public function setIdCategory(?PsCategory $id_category): static
    {
        $this->id_category = $id_category;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;
        return $this;
    }
}
