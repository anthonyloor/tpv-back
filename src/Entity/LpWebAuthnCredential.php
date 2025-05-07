<?php
namespace App\Entity;

use App\Repository\WebAuthnCredentialRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebAuthnCredentialRepository::class)]
class LpWebAuthnCredential
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", unique: true)]
    private string $credentialId;

    #[ORM\Column(type: "string")]
    private string $publicKey;

    #[ORM\Column(type: "string")]
    private string $userHandle;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $name = null; // Nombre amigable para el dispositivo (opcional)

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: PsEmployee::class)]
    #[ORM\JoinColumn(nullable: false)]
    private PsEmployee $user;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // Getters y setters abajo

    public function getId(): int
    {
        return $this->id;
    }

    public function getCredentialId(): string
    {
        return $this->credentialId;
    }

    public function setCredentialId(string $credentialId): self
    {
        $this->credentialId = $credentialId;
        return $this;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function setPublicKey(string $publicKey): self
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    public function getUserHandle(): string
    {
        return $this->userHandle;
    }

    public function setUserHandle(string $userHandle): self
    {
        $this->userHandle = $userHandle;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUser(): PsEmployee
    {
        return $this->user;
    }

    public function setUser(PsEmployee $user): self
    {
        $this->user = $user;
        return $this;
    }
}
