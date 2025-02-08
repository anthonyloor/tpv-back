<?php

namespace App\EntityFajasMaylu;

use App\Repository\PsCustomerRepository;
use App\EntityCommon\PsCustomersBase;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsCustomerRepository::class)]
class PsCustomer extends PsCustomersBase
{
    private $origin = 'maylu';
}
