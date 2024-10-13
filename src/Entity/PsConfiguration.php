<?php

namespace App\Entity;

use App\Repository\PsConfigurationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsConfigurationRepository::class)]
class PsConfiguration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id_configuration ')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false,name:'id_shop_group')]
    private ?PsShopGroup $idShopGroup = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false,name:'id_shop')]
    private ?PsShop $idShop = null;

    #[ORM\Column(length: 254)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateUpd = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdShopGroup(): ?PsShopGroup
    {
        return $this->idShopGroup;
    }

    public function setIdShopGroup(?PsShopGroup $idShopGroup): static
    {
        $this->idShopGroup = $idShopGroup;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): static
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->dateUpd;
    }

    public function setDateUpd(\DateTimeInterface $dateUpd): static
    {
        $this->dateUpd = $dateUpd;

        return $this;
    }
}
