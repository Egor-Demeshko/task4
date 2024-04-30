<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\UserDetails;

// #[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[ORM\MappedSuperclass]
#[Assert\DisableAutoMapping]
class Registration
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    // private ?int $id = null;

    // #[Assert\Type(type: UserDetails::class)]
    // #[Assert\Valid]
    protected ?UserDetails $details = null;
    public function setDetails(UserDetails $details): void
    {
        $this->details = $details;
    }

    public function getDetails(): ?UserDetails
    {
        return $this->details;
    }
}
