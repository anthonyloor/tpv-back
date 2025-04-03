<?php

namespace App\Entity;

use App\Repository\PsSpecificPriceRuleConditionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsSpecificPriceRuleConditionRepository::class)]
#[ORM\Table(name: "ps_specific_price_rule_condition")]
class PsSpecificPriceRuleCondition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_specific_price_rule_condition = null;

    #[ORM\ManyToOne(targetEntity: PsSpecificPriceRuleConditionGroup::class)]
    #[ORM\JoinColumn(name: "id_specific_price_rule_condition_group", referencedColumnName: "id_specific_price_rule_condition_group", nullable: false)]
    private ?PsSpecificPriceRuleConditionGroup $id_specific_price_rule_condition_group = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    // Getters y Setters

    public function getIdSpecificPriceRuleCondition(): ?int
    {
        return $this->id_specific_price_rule_condition;
    }

    public function getIdSpecifiPriceRuleConditionGroup(): ?PsSpecificPriceRuleConditionGroup
    {
        return $this->id_specific_price_rule_condition_group;
    }

    public function setIdSpecificPriceRule(?PsSpecificPriceRuleConditionGroup $id_specific_price_rule_condition_group): static
    {
        $this->id_specific_price_rule_condition_group = $id_specific_price_rule_condition_group;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;
        return $this;
    }
}
