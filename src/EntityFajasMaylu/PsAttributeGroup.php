<?php

namespace App\EntityFajasMaylu;

use App\Repository\PsAttributeGroupFajasMayluRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsAttributeGroupFajasMayluRepository::class)]
#[ORM\Table(name: "ps_attribute_group", schema: 'fajasmaylu_ps')]

class PsAttributeGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idAttributeGroup = null;

    #[ORM\Column]
    private ?bool $isColorGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $groupType = null;

    #[ORM\Column]
    private ?int $position = null;

    public function getIdAttributeGroup(): ?int
    {
        return $this->idAttributeGroup;
    }

    public function isIsColorGroup(): ?bool
    {
        return $this->isColorGroup;
    }

    public function setIsColorGroup(bool $isColorGroup): static
    {
        $this->isColorGroup = $isColorGroup;

        return $this;
    }

    public function getGroupType(): ?string
    {
        return $this->groupType;
    }

    public function setGroupType(string $groupType): static
    {
        $this->groupType = $groupType;

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
