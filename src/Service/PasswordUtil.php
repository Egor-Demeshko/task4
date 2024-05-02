<?php

namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PasswordUtil
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function hashPassword(mixed $user): void
    {
        if (!$user instanceof UserInterface || !$user instanceof PasswordAuthenticatedUserInterface) {
            return;
        }

        $password = $user->getPassword();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

        $user->setPassword($hashedPassword);
    }


    public function isPasswordValid(mixed $user, string $password): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $password);
    }
}
