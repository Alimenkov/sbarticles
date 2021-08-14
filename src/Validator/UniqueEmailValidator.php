<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UnexpectedValueException;

class UniqueEmailValidator extends ConstraintValidator
{
    private UserRepository $userRepository;
    private Security $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }


    public function validate($value, Constraint $constraint)
    {

        /* @var $constraint \App\Validator\UniqueEmail */

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'value');
        }

        /* @var $user UserInterface */

        $user = $this->security->getUser();

        if (!empty($user) && $user->getEmail() == $value) {
            return;
        }

        $user = $this->userRepository->findOneBy(['email' => $value]);

        if (!$user) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
