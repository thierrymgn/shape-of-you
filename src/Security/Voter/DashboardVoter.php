<?php

namespace App\Security;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\WardrobeItem;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DashboardVoter extends Voter
{
    public const VIEW   = 'view';
    public const EDIT   = 'edit';
    public const DELETE = 'delete';
    public const CREATE = 'create';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE])) {
            return false;
        }
        return $subject instanceof Category 
            || $subject instanceof User 
            || $subject instanceof WardrobeItem;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($subject instanceof WardrobeItem) {
            switch ($attribute) {
                case self::VIEW:
                    return true;
                case self::EDIT:
                case self::DELETE:
                    return $subject->getCustomer() && $subject->getCustomer()->getId() === $user->getId();
                case self::CREATE:
                    return true;
            }
        }

        if ($subject instanceof Category) {
            switch ($attribute) {
                case self::VIEW:
                    return true;
                case self::EDIT:
                case self::DELETE:
                    return false;
                case self::CREATE:
                    return false;
            }
        }

        if ($subject instanceof User) {
            switch ($attribute) {
                case self::VIEW:
                    return true;
                case self::EDIT:
                case self::DELETE:
                    return $subject->getId() === $user->getId();
                case self::CREATE:
                    return false;
            }
        }

        return false;
    }
}
