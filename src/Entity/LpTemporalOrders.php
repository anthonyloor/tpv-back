<?php

namespace App\Entity;

use App\Repository\LpPosSessionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: LpTemporalOrdersRepository::class)]
class LpTemporalOrders
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    private ?string $license = null;

    #[ORM\Column]
    private ?int $id_stock_available = null;

    public function setIdStockAvailable(?int $id_stock_available): self
    {
        $this->id_stock_available = $id_stock_available;
        return $this;
    }

    public function getIdStockAvailable(): ?int
    {
        return $this->id_stock_available;
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
}