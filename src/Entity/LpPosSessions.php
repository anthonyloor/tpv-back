<?php

namespace App\Entity;

use App\Repository\LpPosSessionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: LpPosSessionsRepository::class)]
class LpPosSessions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_pos_sessions = null;

    #[ORM\Column]
    private ?int $id_shop = null;

    #[ORM\Column]
    private ?int $id_employee_open = null;

    #[ORM\Column]
    private ?int $id_employee_close = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_close = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $init_cash = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $final_cash = null;
    
    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $total_cash = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $total_card = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $total_bizum = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToOne(targetEntity: LpLicense::class)]
    #[ORM\JoinColumn(name: 'license', referencedColumnName: 'license', onDelete: 'CASCADE')]
    private ?LpLicense $license = null;

    // Getters y Setters...

    public function getIdPosSessions(): ?int
    {
        return $this->id_pos_sessions;
    }

    public function getIdShop(): ?int
    {
        return $this->id_shop;
    }

    public function setIdShop(?int $id_shop): self
    {
        $this->id_shop = $id_shop;
        return $this;
    }

    public function getIdEmployeeOpen(): ?int
    {
        return $this->id_employee_open;
    }

    public function setIdEmployeeOpen(?int $id_employee_open): self
    {
        $this->id_employee_open = $id_employee_open;
        return $this;
    }

    public function getIdEmployeeClose(): ?int
    {
        return $this->id_employee_close;
    }

    public function setIdEmployeeClose(?int $id_employee_close): self
    {
        $this->id_employee_close = $id_employee_close;
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

    public function getDateClose(): ?\DateTimeInterface
    {
        return $this->date_close;
    }

    public function setDateClose(?\DateTimeInterface $date_close): self
    {
        $this->date_close = $date_close;
        return $this;
    }

    public function getInitCash(): ?string
    {
        return $this->init_cash;
    }

    public function setInitCash(?string $init_cash): self
    {
        $this->init_cash = $init_cash;
        return $this;
    }

    public function getFinalCash(): ?string
    {
        return $this->final_cash;
    }

    public function setFinalCash(?string $final_cash): self
    {
        $this->final_cash = $final_cash;
        return $this;
    }

    public function getTotalCash(): ?string
    {
        return $this->total_cash;
    }

    public function setTotalCash(?string $total_cash): self
    {
        $this->total_cash = $total_cash;
        return $this;
    }

    public function getTotalCard(): ?string
    {
        return $this->total_card;
    }

    public function setTotalCard(?string $total_card): self
    {
        $this->total_card = $total_card;
        return $this;
    }

    public function getTotalBizum(): ?string
    {
        return $this->total_bizum;
    }

    public function setTotalBizum(?string $total_bizum): self
    {
        $this->total_bizum = $total_bizum;
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

    public function getLicense(): ?LpLicense
    {
        return $this->license;
    }

    public function setLicense(?LpLicense $license): self
    {
        $this->license = $license;
        return $this;
    }
}
