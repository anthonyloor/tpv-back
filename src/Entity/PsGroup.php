<?php

namespace App\Entity;

use App\Repository\PsGroupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsGroupRepository::class)]
class PsGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 17, scale: 2)]
    private ?string $reduction = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $priceDisplayMethod = null;

    #[ORM\Column]
    private ?bool $showPrices = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateUpd = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(string $reduction): static
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getPriceDisplayMethod(): ?int
    {
        return $this->priceDisplayMethod;
    }

    public function setPriceDisplayMethod(int $priceDisplayMethod): static
    {
        $this->priceDisplayMethod = $priceDisplayMethod;

        return $this;
    }

    public function isShowPrices(): ?bool
    {
        return $this->showPrices;
    }

    public function setShowPrices(bool $showPrices): static
    {
        $this->showPrices = $showPrices;

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
