<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LpLicenseRepository;

#[ORM\Entity(repositoryClass:LpLicenseRepository::class)]
class LpLicense
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    private ?string $license = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expire_date = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    
    #[ORM\Column]
    private ?int $id_shop = null;

    // Getters and Setters

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(?string $license): self
    {
        $this->license = $license;
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

    public function getExpireDate(): ?\DateTimeInterface
    {
        return $this->expire_date;
    }

    public function setExpireDate(?\DateTimeInterface $expire_date): self
    {
        $this->expire_date = $expire_date;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;
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

    public function getIdShop(): ?int
    {
        return $this->id_shop;
    }

    public function setIdShop(?int $id_shop): ?self
    {
        $this->id_shop = $id_shop;
        return $this;
    }

}
