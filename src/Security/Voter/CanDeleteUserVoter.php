<?php

namespace App\Security\Voter;

use App\Entity\OwnerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class CanDeleteUserVoter extends Voter
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['CAN_DELETE'])
            && $subject instanceof OwnerInterface;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */

        $user = $token->getUser();

        if (!$user instanceof UserInterface || $subject->getOwner() == null) {
            return false;
        }

        if ($user == $subject->getOwner()) {
            return true;
        }

        switch ($attribute) {
            case 'CAN_DELETE':
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }

                break;
        }

        return false;
    }
}
