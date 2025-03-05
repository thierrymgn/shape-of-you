<?php

namespace App\Security\Voter;

use App\Entity\Outfit;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Outfit>
 */
class OutfitVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW], true) && $subject instanceof Outfit;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Outfit $outfit */
        $outfit = $subject;

        $isOwner = $outfit->getCustomer() === $user;

        switch ($attribute) {
            case self::VIEW:
                if ($outfit->isPublic()) {
                    return true;
                }

                return $isOwner;

            case self::EDIT:
            case self::DELETE:
                return $isOwner;
        }

        return false;
    }
}
