<?php

namespace App\Entity;

use App\Repository\PsSpecificPriceRuleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsSpecificPriceRuleRepository::class)]
#[ORM\Table(name: "ps_specific_price_rule")]
class PsSpecificPriceRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_specific_price_rule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "id_shop", referencedColumnName: "id_shop", nullable: false)]
    private ?PsShop $id_shop = null;

    #[ORM\Column]
    private ?int $id_currency = null;

    #[ORM\Column]
    private ?int $id_country = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "id_group", referencedColumnName: "id_group", nullable: false)]
    private ?PsGroup $id_group = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $from_quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $reduction = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $reduction_tax = null;

    #[ORM\Column(length: 10)]
    private ?string $reduction_type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $from = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $to = null;

    // Getters y Setters

    public function getIdSpecificPriceRule(): ?int
    {
        return $this->id_specific_price_rule;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getIdShop(): ?PsShop
    {
        return $this->id_shop;
    }

    public function setIdShop(?PsShop $id_shop): static
    {
        $this->id_shop = $id_shop;
        return $this;
    }

    public function getIdCurrency(): ?int
    {
        return $this->id_currency;
    }

    public function setIdCurrency(?int $id_currency): static
    {
        $this->id_currency = $id_currency;
        return $this;
    }

    public function getIdCountry(): ?int
    {
        return $this->id_country;
    }

    public function setIdCountry(?int $id_country): static
    {
        $this->id_country = $id_country;
        return $this;
    }

    public function getIdGroup(): ?PsGroup
    {
        return $this->id_group;
    }

    public function setIdGroup(?PsGroup $id_group): static
    {
        $this->id_group = $id_group;
        return $this;
    }

    public function getFromQuantity(): ?int
    {
        return $this->from_quantity;
    }

    public function setFromQuantity(int $from_quantity): static
    {
        $this->from_quantity = $from_quantity;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(?string $reduction): static
    {
        $this->reduction = $reduction;
        return $this;
    }

    public function isReductionTax(): ?bool
    {
        return $this->reduction_tax;
    }

    public function setReductionTax(?bool $reduction_tax): static
    {
        $this->reduction_tax = $reduction_tax;
        return $this;
    }

    public function getReductionType(): ?string
    {
        return $this->reduction_type;
    }

    public function setReductionType(string $reduction_type): static
    {
        $this->reduction_type = $reduction_type;
        return $this;
    }

    public function getFrom(): ?\DateTimeInterface
    {
        return $this->from;
    }

    public function setFrom(?\DateTimeInterface $from): static
    {
        $this->from = $from;
        return $this;
    }

    public function getTo(): ?\DateTimeInterface
    {
        return $this->to;
    }

    public function setTo(?\DateTimeInterface $to): static
    {
        $this->to = $to;
        return $this;
    }
}
