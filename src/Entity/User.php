<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Validator as CustomConstrains;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', message: 'Account with such email is already created. Try another one.')]
#[ORM\Table(name: 'user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, PasswordUpgraderInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotNull(message: "Email value should not be empty.")]
    #[Assert\Email]
    #[Assert\Type(type: 'string')]
    # #[CustomConstrains\UserEmail()]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private ?array $roles = null;

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

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
    }

    public function setUserDetails(?UserDetails $details)
    {
        $this->user_details = $details;
    }

    public function getUserDetails(): ?UserDetails
    {
        return $this->user_details;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        if (isset($roles)) {
            $this->roles = $roles;
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
