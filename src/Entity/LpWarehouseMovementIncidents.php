<?php

namespace App\Entity;

use App\Repository\LpWarehouseMovementIncidentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpWarehouseMovementIncidentsRepository::class)]
class LpWarehouseMovementIncidents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_warehouse_movement_incidents = null;

    #[ORM\Column(type: "integer")]
    private ?int $id_warehouse_movement_detail = null;


    #[ORM\Column(type: "string", length: 255)]
    private ?string $description = null;

    // Getters y Setters

    public function getIdWarehouseMovementIncidents(): ?int
    {
        return $this->id_warehouse_movement_incidents;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIdWarehouseMovementDetail(): ?int
    {
        return $this->id_warehouse_movement_detail;
    }

    public function setIdWarehouseMovementDetail(int $id_warehouse_movement_detail): self
    {
        $this->id_warehouse_movement_detail = $id_warehouse_movement_detail;

        return $this;
    }
}
