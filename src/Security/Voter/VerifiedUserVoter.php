<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class VerifiedUserVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['IS_VERIFIED']);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($user->isVerified()) {
            return true;
        }

        return false;
    }
}
