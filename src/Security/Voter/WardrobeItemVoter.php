<?php
namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\WardrobeItem;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WardrobeItemVoter extends Voter
{
    public const EDIT = 'edit';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::EDIT && $subject instanceof WardrobeItem;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false; // L'utilisateur doit être connecté
        }

        /** @var WardrobeItem $wardrobeItem */
        $wardrobeItem = $subject;

        // Vérifie si l'utilisateur est bien le propriétaire du vêtement
        return $wardrobeItem->getCustomer() === $user;
    }
}