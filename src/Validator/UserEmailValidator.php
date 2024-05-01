<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UserEmailValidator extends ConstraintValidator
{
    private ?UserRepository $repository = null;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }
        return;
        if ($this->repository->isEmailUnique($value)) {
            return;
        }


        $this->context->buildViolation($constraint->message)
            ->setParameter('email', $value)
            ->addViolation();
    }
}
