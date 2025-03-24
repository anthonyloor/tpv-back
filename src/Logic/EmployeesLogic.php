<?php

namespace App\Logic;

use App\Entity\PsEmployee;
use Doctrine\ORM\EntityManagerInterface;

class EmployeesLogic
{
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function checkUserRol(int $rol, PsEmployee $user)
    {
        $userRol = $user->getIdProfile();
        if ($userRol == $rol) {
            return true;
        }
        return false;
    }
}