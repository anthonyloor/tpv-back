<?php

namespace App\Entity;

use App\Repository\PsGroupLangRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsGroupLangRepository::class)]
class PsGroupLang
{
    #[ORM\Id]
    #[ORM\Column(name: "id_group", type: "integer")]
    private ?int $id_group = null;

    #[ORM\Id]
    #[ORM\Column(name: "id_lang", type: "integer")]
    private ?int $id_lang = null;

    #[ORM\Column(name:'name')]
    private ?string $name  = null;

    public function getIdGroup(): ?int
    {
        return $this->id_group;
    }

    public function setIdGroup(int $id_group): self
    {
        $this->id_group = $id_group;

        return $this;
    }

    public function getIdLang(): ?int
    {
        return $this->id_lang;
    }

    public function setIdLang(int $id_lang): self
    {
        $this->id_lang = $id_lang;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}