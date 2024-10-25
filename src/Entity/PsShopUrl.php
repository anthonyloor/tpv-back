<?php

namespace App\Entity;

use App\Repository\PsShopUrlRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsShopUrlRepository::class)]
class PsShopUrl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_shop_url = null;

    #[ORM\Column]
    private ?int $id_shop = null;

    #[ORM\Column(length: 255)]
    private ?string $domain = null;

    #[ORM\Column(length: 255)]
    private ?string $domain_ssl = null;

    #[ORM\Column(length: 255)]
    private ?string $physical_uri = null;

    #[ORM\Column(length: 255)]
    private ?string $virtual_uri = null;

    #[ORM\Column]
    private ?bool $main = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getIdShopUrl(): ?int
    {
        return $this->id_shop_url;
    }

    public function getIdShop(): ?int
    {
        return $this->id_shop;
    }

    public function setIdShop(int $id_shop): static
    {
        $this->id_shop = $id_shop;
        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;
        return $this;
    }

    public function getDomainSsl(): ?string
    {
        return $this->domain_ssl;
    }

    public function setDomainSsl(string $domain_ssl): static
    {
        $this->domain_ssl = $domain_ssl;
        return $this;
    }

    public function getPhysicalUri(): ?string
    {
        return $this->physical_uri;
    }

    public function setPhysicalUri(string $physical_uri): static
    {
        $this->physical_uri = $physical_uri;
        return $this;
    }

    public function getVirtualUri(): ?string
    {
        return $this->virtual_uri;
    }

    public function setVirtualUri(string $virtual_uri): static
    {
        $this->virtual_uri = $virtual_uri;
        return $this;
    }

    public function isMain(): ?bool
    {
        return $this->main;
    }

    public function setMain(bool $main): static
    {
        $this->main = $main;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }
}
