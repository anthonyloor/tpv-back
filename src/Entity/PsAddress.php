<?php

namespace App\Entity;

use App\Repository\PsAddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsAddressRepository::class)]
#[ORM\Table(name: "ps_address")]
class PsAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_address")]
    private ?int $id_address = null;

    #[ORM\Column(name: "id_country", type: "integer", nullable: true)]
    private ?int $id_country = null;

    #[ORM\Column(name: "id_state", type: "integer", nullable: true)]
    private ?int $id_state = null;

    #[ORM\Column(name: "id_customer", type: "integer", nullable: true)]
    private ?int $id_customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alias = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $address1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address2 = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $postcode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $other = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phone_mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vat_number = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $dni = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_upd = null;

    #[ORM\Column(type: "boolean", options: ["default" => 1])]
    private ?bool $active = null;

    #[ORM\Column(type: "boolean", options: ["default" => 0])]
    private ?bool $deleted = null;

    private $origin = 'mayret';

    #[ORM\ManyToOne(targetEntity: PsCustomer::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(name: 'id_customer', referencedColumnName: 'id_customer')]
    private ?PsCustomer $customer = null;

    public function getCustomer(): ?PsCustomer
    {
        return $this->customer;
    }

    public function setCustomer(?PsCustomer $customer): static
    {
        $this->customer = $customer;
        return $this;
    }


    // Getters y Setters

    public function getOrigin(): ?string
    {
        return $this->origin;
    }
    public function getId(): ?int
    {
        return $this->id_address;
    }

    public function getIdCountry(): ?int
    {
        return $this->id_country;
    }

    public function setIdCountry(?int $id_country): static
    {
        $this->id_country = $id_country;
        return $this;
    }

    public function getIdState(): ?int
    {
        return $this->id_state;
    }

    public function setIdState(?int $id_state): static
    {
        $this->id_state = $id_state;
        return $this;
    }

    public function getIdCustomer(): ?int
    {
        return $this->id_customer;
    }

    public function setIdCustomer(?int $id_customer): static
    {
        $this->id_customer = $id_customer;
        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): static
    {
        $this->alias = $alias;
        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): static
    {
        $this->address1 = $address1;
        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): static
    {
        $this->address2 = $address2;
        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): static
    {
        $this->postcode = $postcode;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getOther(): ?string
    {
        return $this->other;
    }

    public function setOther(?string $other): static
    {
        $this->other = $other;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhoneMobile(): ?string
    {
        return $this->phone_mobile;
    }

    public function setPhoneMobile(?string $phone_mobile): static
    {
        $this->phone_mobile = $phone_mobile;
        return $this;
    }

    public function getVatNumber(): ?string
    {
        return $this->vat_number;
    }

    public function setVatNumber(?string $vat_number): static
    {
        $this->vat_number = $vat_number;
        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): static
    {
        $this->dni = $dni;
        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): static
    {
        $this->date_add = $date_add;
        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(\DateTimeInterface $date_upd): static
    {
        $this->date_upd = $date_upd;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;
        return $this;
    }
}
