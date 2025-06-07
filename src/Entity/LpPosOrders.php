<?php

namespace App\Entity;

use App\Repository\LpPosOrdersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpPosOrdersRepository::class)]
class LpPosOrders{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_pos_order  = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_order = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_pos_session = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_shop = null;

    #[ORM\Column(type: "string", length: 36)]
    private ?string $license = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_employee = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_amount = null;
    
    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_cash = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_card = null;

    #[ORM\Column(type: "decimal", precision: 17, scale: 2)]
    private ?float $total_bizum = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: "string", length: 36)]
    private ?string $origin = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $num_pedido = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $identificador_rts = null;

    public function getIdentificadorRts(): ?string
    {
        return $this->identificador_rts;
    }

    public function setIdentificadorRts(?string $identificador_rts): self
    {
        $this->identificador_rts = $identificador_rts;
        return $this;
    }

    public function getNumPedido(): ?string
    {
        return $this->num_pedido;
    }

    public function setNumPedido(?string $num_pedido): self
    {
        $this->num_pedido = $num_pedido;
        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;
        return $this;
    }

    public function getIdOrder(): ?int
    {
        return $this->id_order;
    }

    public function setIdOrder(?int $id_order): self
    {
        $this->id_order = $id_order;
        return $this;
    }

    public function getIdPosOrder(): ?int
    {
        return $this->id_pos_order;
    }

    public function setIdPosOrder(?int $id_pos_order): self
    {
        $this->id_pos_order = $id_pos_order;
        return $this;
    }

    public function getIdPosSession(): ?int
    {
        return $this->id_pos_session;
    }

    public function setIdPosSession(?int $id_pos_session): self
    {
        $this->id_pos_session = $id_pos_session;
        return $this;
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

    public function getIdEmployee(): ?int
    {
        return $this->id_employee;
    }

    public function setIdEmployee(?int $id_employee): self
    {
        $this->id_employee = $id_employee;
        return $this;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(?string $license): self
    {
        $this->license = $license;
        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->total_amount;
    }

    public function setTotalAmount(?float $total_amount): self
    {
        $this->total_amount = $total_amount;
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

    public function getTotalCard(): ?float
    {
        return $this->total_card;
    }

    public function setTotalCard(?float $total_card): self
    {
        $this->total_card = $total_card;
        return $this;
    }

    public function getTotalCash(): ?float
    {
        return $this->total_cash;
    }

    public function setTotalCash(?float $total_cash): self
    {
        $this->total_cash = $total_cash;
        return $this;
    }

    public function getTotalBizum(): ?float
    {
        return $this->total_bizum;
    }

    public function setTotalBizum(?float $total_bizum): self
    {
        $this->total_bizum = $total_bizum;
        return $this;
    }

}