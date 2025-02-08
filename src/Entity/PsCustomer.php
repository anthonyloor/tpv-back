<?php

namespace App\Entity;

use App\Repository\PsCustomerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsCustomerRepository::class)]
#[ORM\Table(name: 'ps_customer', schema: 'default')]  // Especifica el esquema si es necesario
class PsCustomer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id_customer')]
    private ?int $id_customer  = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $ape = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $passwd = null;

    #[ORM\Column(name: 'id_shop')]
    private ?int $id_shop  = null;

    #[ORM\Column(name: 'id_gender')]
    private ?int $id_gender  = null;

    #[ORM\Column(name: 'id_default_group')]
    private ?int $id_default_group  = null;

    #[ORM\Column(name: 'id_lang')]
    private ?int $id_lang  = null;

    #[ORM\Column(name: 'id_risk')]
    private ?int $id_risk  = null;

    #[ORM\Column(name: 'last_passwd_gen')]
    private ?\DateTime $last_passwd_gen = null;

    #[ORM\Column(name: 'secure_key')]
    private ?string $secure_key = null;

    #[ORM\Column(name: 'note')]
    private ?string $note = null;

    #[ORM\Column(name: 'active')]
    private ?int $active  = null;

    #[ORM\Column(name: 'is_guest')]
    private ?int $is_guest  = null;

    #[ORM\Column(name: 'deleted')]
    private ?int $deleted  = null;

    #[ORM\Column(name: 'date_add')]
    private ?\DateTime $date_add = null;

    #[ORM\Column(name: 'date_upd')]
    private ?\DateTime $date_upd = null;

    #[ORM\Column(name: 'newsletter')]
    private ?int $newsletter  = null;

    #[ORM\Column(name: 'max_payment_days')]
    private ?int $max_payment_days  = null;

    private ?string $origin = null;

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getMaxPaymentDays(): ?int
    {
        return $this->max_payment_days;
    }

    public function setMaxPaymentDays(?int $max_payment_days): static
    {
        $this->max_payment_days = $max_payment_days;

        return $this;
    }


    public function getNewsletter(): ?int
    {
        return $this->newsletter;
    }

    public function setNewsletter(?int $newsletter): static
    {
        $this->newsletter = $newsletter;

        return $this;
    }


    public function getIdGender(): ?int
    {
        return $this->id_gender;
    }

    public function setIdGender(?int $id_gender): static
    {
        $this->id_gender = $id_gender;

        return $this;
    }

    public function getIdDefaultGroup(): ?int
    {
        return $this->id_default_group;
    }

    public function setIdDefaultGroup(?int $id_default_group): static
    {
        $this->id_default_group = $id_default_group;

        return $this;
    }

    public function getIdLang(): ?int
    {
        return $this->id_lang;
    }

    public function setIdLang(?int $id_lang): static
    {
        $this->id_lang = $id_lang;

        return $this;
    }

    public function getIdRisk(): ?int
    {
        return $this->id_risk;
    }

    public function setIdRisk(?int $id_risk): static
    {
        $this->id_risk = $id_risk;

        return $this;
    }

    public function getLastPasswdGen(): ?\DateTime
    {
        return $this->last_passwd_gen;
    }

    public function setLastPasswdGen(?\DateTime $last_passwd_gen): static
    {
        $this->last_passwd_gen = $last_passwd_gen;

        return $this;
    }

    public function getSecureKey(): ?string
    {
        return $this->secure_key;
    }

    public function setSecureKey(?string $secure_key): static
    {
        $this->secure_key = $secure_key;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(?int $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getIsGuest(): ?int
    {
        return $this->is_guest;
    }

    public function setIsGuest(?int $is_guest): static
    {
        $this->is_guest = $is_guest;

        return $this;
    }

    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    public function setDeleted(?int $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getDateAdd(): ?\DateTime
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTime $date_add): static
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getDateUpd(): ?\DateTime
    {
        return $this->date_upd;
    }

    public function setDateUpd(?\DateTime $date_upd): static
    {
        $this->date_upd = $date_upd;

        return $this;
    }

    public function getIdShop(): ?int
    {
        return $this->id_shop;
    }

    public function setIdShop(?int $id_shop): static
    {
        $this->id_shop = $id_shop;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id_customer;
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getApe(): ?string
    {
        return $this->ape;
    }

    public function setApe(?string $ape): static
    {
        $this->ape = $ape;

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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    public function setPasswd(string $passwd): static
    {
        $this->passwd = $passwd;

        return $this;
    }
}
