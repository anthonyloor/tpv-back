<?php

namespace App\Entity;

use App\Repository\PsLpcrmCouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsLpcrmCouponRepository::class)]
class PsLpcrmCoupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_lpcrm_coupon = null;

    #[ORM\Column]
    private int $id_cart_rule;

    #[ORM\Column(type: 'boolean')]
    private bool $not_combinable = true;

    #[ORM\Column(type: 'boolean')]
    private bool $online_only = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_upd = null;

    public function getIdLpcrmCoupon(): ?int
    {
        return $this->id_lpcrm_coupon;
    }

    public function getIdCartRule(): int
    {
        return $this->id_cart_rule;
    }

    public function setIdCartRule(int $id_cart_rule): self
    {
        $this->id_cart_rule = $id_cart_rule;
        return $this;
    }

    public function isNotCombinable(): bool
    {
        return $this->not_combinable;
    }

    public function setNotCombinable(bool $not_combinable): self
    {
        $this->not_combinable = $not_combinable;
        return $this;
    }

    public function isOnlineOnly(): bool
    {
        return $this->online_only;
    }

    public function setOnlineOnly(bool $online_only): self
    {
        $this->online_only = $online_only;
        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;
        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;
        return $this;
    }
}
