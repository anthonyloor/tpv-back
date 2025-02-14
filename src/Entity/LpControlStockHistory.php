<?php

namespace App\Entity;
use App\Repository\LpControlStockHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpControlStockHistoryRepository::class)]
class LpControlStockHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_control_stock_history;

    #[ORM\Column(type: 'integer')]
    private int $id_control_stock;

    #[ORM\Column(type: 'string', length: 255)]
    private string $reason;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    // Getters and setters for each property

    public function getIdControlStockHistory(): ?int
    {
        return $this->id_control_stock_history;
    }

    public function getIdControlStock(): ?int
    {
        return $this->id_control_stock;
    }

    public function setIdControlStock(int $id_control_stock): self
    {
        $this->id_control_stock = $id_control_stock;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }
}