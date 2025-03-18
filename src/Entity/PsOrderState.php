<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\PsOrderStateRepository;

#[ORM\Entity(repositoryClass:PsOrderStateRepository::class)]
#[ORM\Table(name: "ps_order_state")]
class PsOrderState
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idOrderState;

    #[ORM\Column(type: "boolean")]
    private bool $invoice;

    #[ORM\Column(type: "boolean")]
    private bool $sendEmail;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $moduleName;

    #[ORM\Column(type: "string", length: 32, nullable: true)]
    private ?string $color;

    #[ORM\Column(type: "boolean")]
    private bool $unremovable;

    #[ORM\Column(type: "boolean")]
    private bool $hidden;

    #[ORM\Column(type: "boolean")]
    private bool $logable;

    #[ORM\Column(type: "boolean")]
    private bool $delivery;

    #[ORM\Column(type: "boolean")]
    private bool $shipped;

    #[ORM\Column(type: "boolean")]
    private bool $paid;

    #[ORM\Column(type: "boolean")]
    private bool $pdfInvoice;

    #[ORM\Column(type: "boolean")]
    private bool $pdfDelivery;

    #[ORM\Column(type: "boolean")]
    private bool $deleted;

    #[ORM\OneToMany(targetEntity: PsOrderStateLang::class, mappedBy: "orderState")]
    private Collection $orderStateLangs;

    // Getters y Setters

    public function getIdOrderState(): int
    {
        return $this->idOrderState;
    }

    public function getInvoice(): bool
    {
        return $this->invoice;
    }

    public function setInvoice(bool $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    public function getSendEmail(): bool
    {
        return $this->sendEmail;
    }

    public function setSendEmail(bool $sendEmail): self
    {
        $this->sendEmail = $sendEmail;
        return $this;
    }

    public function getModuleName(): ?string
    {
        return $this->moduleName;
    }

    public function setModuleName(?string $moduleName): self
    {
        $this->moduleName = $moduleName;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function getUnremovable(): bool
    {
        return $this->unremovable;
    }

    public function setUnremovable(bool $unremovable): self
    {
        $this->unremovable = $unremovable;
        return $this;
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;
        return $this;
    }

    public function __construct()
    {
        $this->orderStateLangs = new ArrayCollection();
    }


    public function getOrderStateLang(): ?PsOrderStateLang
    {
        return $this->orderStateLangs->first() ?: null;
    }

    
       
}