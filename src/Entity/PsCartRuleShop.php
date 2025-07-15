<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PsCartRuleShopRepository;

#[ORM\Entity(repositoryClass: PsCartRuleShopRepository::class)]
#[ORM\Table(name: 'ps_cart_rule_shop')]
class PsCartRuleShop
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $id_cart_rule = null;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $id_shop = null;

    public function getIdCartRule(): ?int
    {
        return $this->id_cart_rule;
    }

    public function setIdCartRule(?int $id_cart_rule): self
    {
        $this->id_cart_rule = $id_cart_rule;
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
}
