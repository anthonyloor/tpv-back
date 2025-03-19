<?php

namespace App\EntityFajasMaylu;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Table(name: "ps_category_lang", schema: 'fajasmaylu_ps')]
class PsCategoryLang
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_category = null;

    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_lang = null;

    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_shop  = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $link_rewrite = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $meta_title = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $meta_description = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $meta_keywords = null;

    // Getters y Setters...

    public function getIdShop(): ?int
    {
        return $this->id_shop;
    }
    public function getIdCategory(): ?int
    {
        return $this->id_category;
    }

    public function setIdCategory(?int $id_category): self
    {
        $this->id_category = $id_category;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getLinkRewrite(): ?string
    {
        return $this->link_rewrite;
    }

    public function setLinkRewrite(?string $link_rewrite): self
    {
        $this->link_rewrite = $link_rewrite;
        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->meta_title;
    }

    public function setMetaTitle(?string $meta_title): self
    {
        $this->meta_title = $meta_title;
        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): self
    {
        $this->meta_description = $meta_description;
        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->meta_keywords;
    }

    public function setMetaKeywords(?string $meta_keywords): self
    {
        $this->meta_keywords = $meta_keywords;
        return $this;
    }
}