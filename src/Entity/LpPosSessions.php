<?php

namespace App\Entity;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class LpPosSessions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_pos_sessions = null;

    #[ORM\Column]
    private ?int $id_shop = null;

    #[ORM\Column]
    private ?int $id_employee_open = null;

    #[ORM\Column]
    private ?int $id_employee_close = null;

    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $daate_add = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $init_balance = null;
    
    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $total_cash = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $total_card = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 64, scale: 2)]
    private ?string $total_bizum = null;

    #[ORM\Column]
    private ?bool $active = null;
}