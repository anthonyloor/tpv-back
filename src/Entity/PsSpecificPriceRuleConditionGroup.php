<?php

namespace App\Entity;

use App\Repository\PsSpecificPriceRuleConditionGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsSpecificPriceRuleConditionGroupRepository::class)]
#[ORM\Table(name: "ps_specific_price_rule_condition_group")]
class PsSpecificPriceRuleConditionGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_specific_price_rule_condition_group = null;

    #[ORM\ManyToOne(targetEntity: PsSpecificPriceRule::class)]
    #[ORM\JoinColumn(name: "id_specific_price_rule", referencedColumnName: "id_specific_price_rule", nullable: false)]
    private ?PsSpecificPriceRule $id_specific_price_rule = null;

    // Getters y Setters

    public function getIdSpecificPriceRuleConditionGroup(): ?int
    {
        return $this->id_specific_price_rule_condition_group;
    }

    public function getIdSpecificPriceRule(): ?PsSpecificPriceRule
    {
        return $this->id_specific_price_rule;
    }

    public function setIdSpecificPriceRule(?PsSpecificPriceRule $id_specific_price_rule): static
    {
        $this->id_specific_price_rule = $id_specific_price_rule;
        return $this;
    }
}
