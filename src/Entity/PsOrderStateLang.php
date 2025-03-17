<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsOrderStateLangRepository::class)]
#[ORM\Table(name: "ps_order_state_lang")]
class PsOrderStateLang
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: PsOrderState::class)]
    #[ORM\JoinColumn(name: "id_order_state", referencedColumnName: "id_order_state", nullable: false)]
    private PsOrderState $orderState;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idLang;

    #[ORM\Column(type: "string", length: 64)]
    private string $name;

    #[ORM\Column(type: "string", length: 64)]
    private string $template;

    public function getOrderState(): PsOrderState
    {
        return $this->orderState;
    }

    public function setOrderState(PsOrderState $orderState): self
    {
        $this->orderState = $orderState;
        return $this;
    }

    public function getIdLang(): int
    {
        return $this->idLang;
    }

    public function setIdLang(int $idLang): self
    {
        $this->idLang = $idLang;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }
}
