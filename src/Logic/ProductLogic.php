<?php

namespace App\Logic;

use App\Entity\LpControlStock;
use Doctrine\ORM\EntityManagerInterface;


class ProductLogic
{
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }



}