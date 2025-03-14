<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "ps_order_history")]
class PsOrderHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_order_history = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_employee = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_order = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_order_state = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $date_add = null;

    // Getters y Setters

    public function getIdOrderHistory(): ?int
    {
        return $this->id_order_history;
    }

    public function getIdEmployee(): ?int
    {
        return $this->id_employee;
    }

    public function setIdEmployee(?int $id_employee): static
    {
        $this->id_employee = $id_employee;
        return $this;
    }

    public function getIdOrder(): ?int
    {
        return $this->id_order;
    }

    public function setIdOrder(?int $id_order): static
    {
        $this->id_order = $id_order;
        return $this;
    }

    public function getIdOrderState(): ?int
    {
        return $this->id_order_state;
    }

    public function setIdOrderState(?int $id_order_state): static
    {
        $this->id_order_state = $id_order_state;
        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTimeInterface $date_add): static
    {
        $this->date_add = $date_add;
        return $this;
    }
}
