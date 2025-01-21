<?php

namespace App\Entity;
use App\Repository\PsCustomerGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsCustomerGroupRepository::class)]
class PsCustomerGroup
{
    #[ORM\Column(name:'id_customer')]
    private ?int $id_customer  = null;


    #[ORM\Column(name:'id_group')]
    private ?int $id_group  = null;

    public function getIdCustomer(): ?int
    {
        return $this->id_customer;
    }

    public function setIdCustomer(int $id_customer): self
    {
        $this->id_customer = $id_customer;

        return $this;
    }

    public function getIdGroup(): ?int
    {
        return $this->id_group;
    }

    public function setIdGroup(int $id_group): self
    {
        $this->id_group = $id_group;

        return $this;
    }
}