<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotNull(message: "Email value should not be empty.")]
    #[Assert\Email]
    #[Assert\Regex('/[a-z0-9._%+-]+@[a-z0-9.-]+?[.]{1}[a-z]{2,}$/', message: "Email should be like: exp@exp.by")]
    #[Assert\Type(type: 'string')]
    private ?string $email = null;

    #[ORM\Column(length: 255, unique: false)]
    #[Assert\NotNull(message: "Password value should not be empty.")]
    #[Assert\Type(type: 'string')]
    private ?string $password = null;

    #[ORM\OneToOne(targetEntity: UserDetails::class, mappedBy: 'user')]
    protected ?UserDetails $user_details = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function setUserDetails(?UserDetails $details)
    {
        $this->user_details = $details;
    }

    public function getUserDetails(): ?UserDetails
    {
        return $this->user_details;
    }

    public function validate(string $email, ExecutionContextInterface $context, mixed $payload): void
    {
        $context;
    }
}
