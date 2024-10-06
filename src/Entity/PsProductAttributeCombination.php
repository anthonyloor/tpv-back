<?php

namespace App\Entity;

use App\Repository\PsProductAttributeCombinationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsProductAttributeCombinationRepository::class)]
class PsProductAttributeCombination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idAttribute = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product_attribute',nullable: false)]
    private ?PsProductAttribute $idProductAttribute = null;

    public function getIdAttribute(): ?int
    {
        return $this->idAttribute;
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
