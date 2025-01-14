<?php

namespace App\Entity;

use App\Repository\PsAttributeLangRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsAttributeLangRepository::class)]
class PsAttributeLang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_lang = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_attribute',nullable: false)]
    private ?PsAttribute $idAttribute = null;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    public function getIdLang(): ?int
    {
        return $this->id_lang;
    }

    public function getIdAttribute(): ?PsAttribute
    {
        return $this->idAttribute;
    }

    public function setIdAttribute(?PsAttribute $idAttribute): static
    {
        $this->idAttribute = $idAttribute;

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
}
