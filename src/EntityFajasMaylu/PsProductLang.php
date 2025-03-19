<?php

namespace App\EntityFajasMaylu;

use App\RepositoryFajasMaylu\PsProductLangFajasMayluRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsProductLangFajasMayluRepository::class)]
#[ORM\Table(name: "ps_product_lang", schema: 'fajasmaylu_ps')]

class PsProductLang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_lang = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_product',nullable: false)]
    private ?PsProduct $id_product = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:'id_shop',nullable: false)]
    private ?PsShop $idShop = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionShort = null;

    #[ORM\Column(length: 128)]
    private ?string $linkRewrite = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $metaDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaKeywords = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $metaTitle = null;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $availableNow = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $availableLater = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryInStock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryOutStock = null;

    public function getIdLang(): ?int
    {
        return $this->id_lang;
    }

    public function getIdProduct(): ?PsProduct
    {
        return $this->id_product;
    }

    public function setIdProduct(?PsProduct $id_product): static
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getIdShop(): ?PsShop
    {
        return $this->idShop;
    }

    public function setIdShop(?PsShop $idShop): static
    {
        $this->idShop = $idShop;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescriptionShort(): ?string
    {
        return $this->descriptionShort;
    }

    public function setDescriptionShort(?string $descriptionShort): static
    {
        $this->descriptionShort = $descriptionShort;

        return $this;
    }

    public function getLinkRewrite(): ?string
    {
        return $this->linkRewrite;
    }

    public function setLinkRewrite(string $linkRewrite): static
    {
        $this->linkRewrite = $linkRewrite;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): static
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): static
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;

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

    public function getAvailableNow(): ?string
    {
        return $this->availableNow;
    }

    public function setAvailableNow(?string $availableNow): static
    {
        $this->availableNow = $availableNow;

        return $this;
    }

    public function getAvailableLater(): ?string
    {
        return $this->availableLater;
    }

    public function setAvailableLater(?string $availableLater): static
    {
        $this->availableLater = $availableLater;

        return $this;
    }

    public function getDeliveryInStock(): ?string
    {
        return $this->deliveryInStock;
    }

    public function setDeliveryInStock(?string $deliveryInStock): static
    {
        $this->deliveryInStock = $deliveryInStock;

        return $this;
    }

    public function getDeliveryOutStock(): ?string
    {
        return $this->deliveryOutStock;
    }

    public function setDeliveryOutStock(?string $deliveryOutStock): static
    {
        $this->deliveryOutStock = $deliveryOutStock;

        return $this;
    }
}
