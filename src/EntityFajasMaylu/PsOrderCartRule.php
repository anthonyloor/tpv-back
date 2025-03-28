<?php

namespace App\EntityFajasMaylu;

use App\RepositoryFajasMaylu\PsOrderCartRuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsOrderCartRuleRepository::class)]
class PsOrderCartRule
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_order_cart_rule = null;

    #[ORM\Column]
    private int $id_order;

    #[ORM\Column]
    private int $id_cart_rule;

    #[ORM\Column]
    private ?int $id_order_invoice;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private float $value;

    #[ORM\Column]
    private float $value_tax_excl;

    #[ORM\Column]
    private bool $free_shipping;

    // Getters and Setters
    public function getIdOrderCartRule(): int
    {
        return $this->id_order_cart_rule;
    }

    public function getIdOrder(): int
    {
        return $this->id_order;
    }

    public function setIdOrder(int $id_order): void
    {
        $this->id_order = $id_order;
    }

    public function getIdCartRule(): int
    {
        return $this->id_cart_rule;
    }

    public function setIdCartRule(int $id_cart_rule): void
    {
        $this->id_cart_rule = $id_cart_rule;
    }

    public function getIdOrderInvoice(): ?int
    {
        return $this->id_order_invoice;
    }

    public function setIdOrderInvoice(?int $id_order_invoice): void
    {
        $this->id_order_invoice = $id_order_invoice;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getValueTaxExcl(): float
    {
        return $this->value_tax_excl;
    }

    public function setValueTaxExcl(float $value_tax_excl): void
    {
        $this->value_tax_excl = $value_tax_excl;
    }

    public function getFreeShipping(): bool
    {
        return $this->free_shipping;
    }

    public function setFreeShipping(bool $free_shipping): void
    {
        $this->free_shipping = $free_shipping;
    }

}