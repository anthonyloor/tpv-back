<?php

namespace App\EntityFajasMaylu;

use App\RepositoryFajasMaylu\PsProductAttributeCombinationFajasMayluRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsProductAttributeCombinationFajasMayluRepository::class)]
#[ORM\Table(name: "ps_product_attribute_combination", schema: 'fajasmaylu_ps')]

class PsProductAttributeCombination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idAttribute = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product_attribute',nullable: false)]
    private ?PsProductAttribute $id_product_attribute = null;

    public function getIdAttribute(): ?int
    {
        return $this->idAttribute;
    }

    public function getIdProductAttribute(): ?PsProductAttribute
    {
        return $this->id_product_attribute;
    }

    public function setIdProductAttribute(?PsProductAttribute $id_product_attribute): static
    {
        $this->id_product_attribute = $id_product_attribute;

        return $this;
    }
}
