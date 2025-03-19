<?php

namespace App\EntityFajasMaylu;

use App\Repository\PsAttributeFajasMayluRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsAttributeFajasMayluRepository::class)]
class PsAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idAttribute = null;

    #[ORM\Column(length: 32)]
    private ?string $color = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_attribute_group',nullable: false)]
    private ?PsAttributeGroup $idAttributeGroup = null;

    public function getIdAttribute(): ?int
    {
        return $this->idAttribute;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

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

    public function getIdAttributeGroup(): ?PsAttributeGroup
    {
        return $this->idAttributeGroup;
    }

    public function setIdAttributeGroup(?PsAttributeGroup $idAttributeGroup): static
    {
        $this->idAttributeGroup = $idAttributeGroup;

        return $this;
    }
}
