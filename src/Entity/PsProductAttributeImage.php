<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class PsProductAttributeImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_product_attribute  = null;

    #[ORM\Column]
    private ?int $id_image = null;


    // Getter y Setter para id_attribute
    public function getIdAttribute(): ?int
    {
        return $this->id_product_attribute ;
    }

    public function setIdAttribute(?int $id_attribute): self
    {
        $this->id_attribute = $id_attribute;
        return $this;
    }

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

}