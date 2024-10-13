<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class PsImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_image = null;

    #[ORM\Column]
    private ?int $id_product = null;

    // Getter y Setter para id_image
    public function getIdImage(): ?int
    {
        return $this->id_image;
    }

    public function setIdImage(?int $id_image): self
    {
        $this->id_image = $id_image;
        return $this;
    }

    // Getter y Setter para id_product
    public function getIdProduct(): ?int
    {
        return $this->id_product;
    }

    public function setIdProduct(?int $id_product): self
    {
        $this->id_product = $id_product;
        return $this;
    }
}
