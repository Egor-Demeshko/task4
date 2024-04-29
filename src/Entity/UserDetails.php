<?php

namespace App\Entity;

use App\Repository\UserDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserDetailsRepository::class)]
class UserDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: "Name should not be empty.")]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Registration time should not be empty.")]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $registrated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $last_logined_at = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotNull(message: "The status should not be empty.")]
    #[Assert\NotBlank]
    private ?string $status = null;

    #[ORM\OneToOne(targetEntity: "User", cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: "id", nullable: false)]
    private ?User $user_id = null;

    #[Assert\Type(type: User::class)]
    #[Assert\Valid]
    protected ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRegistratedAt(): ?\DateTimeImmutable
    {
        return $this->registrated_at;
    }

    public function setRegistratedAt(\DateTimeImmutable $registrated_at): static
    {
        $this->registrated_at = $registrated_at;

        return $this;
    }

    public function getLastLoginedAt(): ?\DateTimeInterface
    {
        return $this->last_logined_at;
    }

    public function setLastLoginedAt(\DateTimeInterface $last_logined_at): static
    {
        $this->last_logined_at = $last_logined_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
