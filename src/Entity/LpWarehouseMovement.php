<?php

namespace App\Entity;

use App\Repository\LpWarehouseMovementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpWarehouseMovementRepository::class)]
class LpWarehouseMovement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_warehouse_movement = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_shop_origin = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_shop_destiny = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $status = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $type = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_recived = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_excute = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_modified = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_employee = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $modify_reason = null;

    // Getters y Setters

    public function getIdWarehouseMovement(): ?int
    {
        return $this->id_warehouse_movement;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getIdShopOrigin(): ?int
    {
        return $this->id_shop_origin;
    }

    public function setIdShopOrigin(?int $id_shop_origin): self
    {
        $this->id_shop_origin = $id_shop_origin;
        return $this;
    }

    public function getIdShopDestiny(): ?int
    {
        return $this->id_shop_destiny;
    }

    public function setIdShopDestiny(?int $id_shop_destiny): self
    {
        $this->id_shop_destiny = $id_shop_destiny;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
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

    public function getDateRecived(): ?\DateTimeInterface
    {
        return $this->date_recived;
    }

    public function setDateRecived(?\DateTimeInterface $date_recived): self
    {
        $this->date_recived = $date_recived;
        return $this;
    }

    public function getDateExcute(): ?\DateTimeInterface
    {
        return $this->date_excute;
    }

    public function setDateExcute(?\DateTimeInterface $date_excute): self
    {
        $this->date_excute = $date_excute;
        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(?\DateTimeInterface $date_modified): self
    {
        $this->date_modified = $date_modified;
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

    public function getModifyReason(): ?string
    {
        return $this->modify_reason;
    }

    public function setModifyReason(?string $modify_reason): self
    {
        $this->modify_reason = $modify_reason;
        return $this;
    }
}
