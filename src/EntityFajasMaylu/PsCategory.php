<?php

namespace App\EntityFajasMaylu;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Table(name: "ps_category", schema: 'fajasmaylu_ps')]
class PsCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_category = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_parent = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $level_depth = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_upd = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $is_root_category = null;

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
    public function getIdCategory(): ?int
    {
        return $this->id_category;
    }

    public function getIdParent(): ?int
    {
        return $this->id_parent;
    }

    public function setIdParent(?int $id_parent): self
    {
        $this->id_parent = $id_parent;
        return $this;
    }

    public function getLevelDepth(): ?int
    {
        return $this->level_depth;
    }

    public function setLevelDepth(?int $level_depth): self
    {
        $this->level_depth = $level_depth;
        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;
        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(?\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getIsRootCategory(): ?bool
    {
        return $this->is_root_category;
    }

    public function setIsRootCategory(?bool $is_root_category): self
    {
        $this->is_root_category = $is_root_category;
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