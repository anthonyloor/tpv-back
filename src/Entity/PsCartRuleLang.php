<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PsCartRuleLang
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private ?int $id_cart_rule = null;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private ?int $id_lang = null;

    #[ORM\Column(type: "string", length: 254, nullable: true)]
    private ?string $name = null;

    // Getters y Setters

    public function getIdCartRule(): ?int
    {
        return $this->id_cart_rule;
    }

    public function setIdCartRule(?int $id_cart_rule): self
    {
        $this->id_cart_rule = $id_cart_rule;
        return $this;
    }

    public function getIdLang(): ?int
    {
        return $this->id_lang;
    }

    public function setIdLang(?int $id_lang): self
    {
        $this->id_lang = $id_lang;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
