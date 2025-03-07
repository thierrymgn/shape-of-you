<?php

namespace App\Security;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\WardrobeItem;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, mixed>
 */
class DashboardVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';
    public const CREATE = 'create';

    protected function supports(string $attribute, $subject): bool
    {
        // Le voter gère uniquement ces attributs et pour les entités Category, User ou WardrobeItem.
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE])) {
            return false;
        }

        return $subject instanceof Category
            || $subject instanceof User
            || $subject instanceof WardrobeItem;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Récupérer l'utilisateur connecté
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // Vérification manuelle : si l'utilisateur possède ROLE_ADMIN, il a accès à tout.
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // Logique spécifique selon le type de sujet
        if ($subject instanceof WardrobeItem) {
            switch ($attribute) {
                case self::VIEW:
                    return true;
                case self::EDIT:
                case self::DELETE:
                    return $subject->getCustomer() && $subject->getCustomer()->getId() === $user->getId();
                case self::CREATE:
                    // Autoriser la création pour tout utilisateur connecté
                    return true;
            }
        }

        if ($subject instanceof Category) {
            switch ($attribute) {
                case self::VIEW:
                    return true;
                case self::EDIT:
                case self::DELETE:
                    // Seuls les admins (déjà gérés) peuvent modifier/supprimer les catégories
                    return false;
                case self::CREATE:
                    // Par défaut, création non autorisée pour les non-admins
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
