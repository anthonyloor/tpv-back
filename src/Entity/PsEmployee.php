<?php

namespace App\Entity;

use App\Repository\PsAttributeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsAttributeRepository::class)]
class PsEmployee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_employee = null;

    #[ORM\Column]
    private ?int $id_profile = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $passwd  = null;

    #[ORM\Column]
    private ?bool $active = null;

    // Getters and Setters

    public function getIdEmployee(): ?int
    {
        return $this->id_employee;
    }

    public function setIdEmployee(?int $id_employee): self
    {
        $this->id_employee = $id_employee;
        return $this;
    }

    public function getIdProfile(): ?int
    {
        return $this->id_profile;
    }

    public function setIdProfile(?int $id_profile): self
    {
        $this->id_profile = $id_profile;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    public function setPasswd(?string $passwd): self
    {
        $this->passwd = $passwd;
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
}
