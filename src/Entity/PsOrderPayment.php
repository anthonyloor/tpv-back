<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "ps_order_payment")]
class PsOrderPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id_order_payment = null;

    #[ORM\Column(type: "string", length: 9, nullable: true)]
    private ?string $order_reference = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $id_currency = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2, nullable: true)]
    private ?float $amount = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $payment_method = null;

    #[ORM\Column(type: "decimal", precision: 13, scale: 6, nullable: true, options: ["default" => 1.000000])]
    private ?float $conversion_rate = 1.000000;

    #[ORM\Column(type: "string", length: 254, nullable: true)]
    private ?string $transaction_id = null;

    #[ORM\Column(type: "string", length: 254, nullable: true)]
    private ?string $card_number = null;

    #[ORM\Column(type: "string", length: 254, nullable: true)]
    private ?string $card_brand = null;

    #[ORM\Column(type: "string", length: 2, nullable: true)]
    private ?string $card_expiration = null;

    #[ORM\Column(type: "string", length: 254, nullable: true)]
    private ?string $card_holder = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $date_add = null;

    // Getters y Setters
    public function getIdOrderPayment(): ?int
    {
        return $this->id_order_payment;
    }

    public function getOrderReference(): ?string
    {
        return $this->order_reference;
    }

    public function setOrderReference(?string $order_reference): static
    {
        $this->order_reference = $order_reference;
        return $this;
    }

    public function getIdCurrency(): ?int
    {
        return $this->id_currency;
    }

    public function setIdCurrency(?int $id_currency): static
    {
        $this->id_currency = $id_currency;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(?string $payment_method): static
    {
        $this->payment_method = $payment_method;
        return $this;
    }

    public function getConversionRate(): ?float
    {
        return $this->conversion_rate;
    }

    public function setConversionRate(?float $conversion_rate): static
    {
        $this->conversion_rate = $conversion_rate;
        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transaction_id;
    }

    public function setTransactionId(?string $transaction_id): static
    {
        $this->transaction_id = $transaction_id;
        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->card_number;
    }

    public function setCardNumber(?string $card_number): static
    {
        $this->card_number = $card_number;
        return $this;
    }

    public function getCardBrand(): ?string
    {
        return $this->card_brand;
    }

    public function setCardBrand(?string $card_brand): static
    {
        $this->card_brand = $card_brand;
        return $this;
    }

    public function getCardExpiration(): ?string
    {
        return $this->card_expiration;
    }

    public function setCardExpiration(?string $card_expiration): static
    {
        $this->card_expiration = $card_expiration;
        return $this;
    }

    public function getCardHolder(): ?string
    {
        return $this->card_holder;
    }

    public function setCardHolder(?string $card_holder): static
    {
        $this->card_holder = $card_holder;
        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTimeInterface $date_add): static
    {
        $this->date_add = $date_add;
        return $this;
    }
}
