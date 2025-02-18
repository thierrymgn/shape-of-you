<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\WardrobeItem;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/* @phpstan-ignore-next-line */
class WardrobeItemVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    /* @phpstan-ignore-next-line */
    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE], true) && $subject instanceof WardrobeItem;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var WardrobeItem $wardrobeItem */
        $wardrobeItem = $subject;

        return $wardrobeItem->getCustomer() === $user;
    }
}
