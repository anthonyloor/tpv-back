<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "lp_pin")]
class LpPin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $idPin = null;

    #[ORM\Column(type: "integer", length: 4)]
    private int $pin;

    #[ORM\Column(type: "datetime")]
    private \DateTime $date_add;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $date_used = null;

    #[ORM\Column(type: "boolean")]
    private bool $active;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(type: "integer", length: 11)]
    private int $id_employee_request;

    #[ORM\Column(type: "integer", length: 11)]
    private int $id_employee_used;


    // Getters y Setters
    public function getIdPin(): ?int
    {
        return $this->idPin;
    }

    public function getPin(): int
    {
        return $this->pin;
    }

    public function setPin(int $pin): self
    {
        $this->pin = $pin;
        return $this;
    }

    public function getDateAdd(): \DateTime
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTime $date_add): self
    {
        $this->date_add = $date_add;
        return $this;
    }

    public function getDateUsed(): ?\DateTime
    {
        return $this->date_used;
    }

    public function setDateUsed(?\DateTime $date_used): self
    {
        $this->date_used = $date_used;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;
        return $this;
    }

    public function getIdEmployeeRequest(): int
    {
        return $this->id_employee_request;
    }

    public function setIdEmployeeRequest(int $id_employee_request): self
    {
        $this->id_employee_request = $id_employee_request;
        return $this;
    }

    public function getIdEmployeeUsed(): int
    {
        return $this->id_employee_used;
    }

    public function setIdEmployeeUsed(int $id_employee_used): self
    {
        $this->id_employee_used = $id_employee_used;
        return $this;
    }
}
