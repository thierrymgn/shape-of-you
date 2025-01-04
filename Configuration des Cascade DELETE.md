# Configuration des Cascade DELETE - Guide Détaillé

## Principe Général
La cascade DELETE détermine ce qui se passe avec les entités liées lorsqu'une entité est supprimée.

## Stratégies par Domaine

### 1. Utilisateur et Profil
```php
class User
{
    /**
     * @ORM\OneToOne(targetEntity=Profile::class, cascade={"all"})
     */
    private $profile;
}
```
- **Stratégie** : CASCADE COMPLET
- **Raison** : Le profil n'a pas de sens sans utilisateur
- **Impact** : La suppression d'un User supprime automatiquement son Profile

### 2. Garde-Robe et Items
```php
class WardrobeItem
{
    /**
     * @ORM\OneToOne(targetEntity=Acquisition::class, cascade={"all"})
     */
    private $acquisition;

    /**
     * @ORM\OneToMany(targetEntity=OutfitItem::class, mappedBy="wardrobeItem", cascade={"remove"})
     */
    private $outfitItems;
}
```
- **Stratégie** : 
  - CASCADE pour Acquisition
  - CASCADE pour OutfitItem
  - PAS DE CASCADE pour Category
- **Raison** : 
  - L'acquisition est une information dépendante
  - Les références dans les tenues doivent être nettoyées
  - Les catégories sont indépendantes

### 3. Tenues et Compositions
```php
class Outfit
{
    /**
     * @ORM\OneToMany(targetEntity=OutfitItem::class, mappedBy="outfit", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $outfitItems;

    /**
     * @ORM\OneToMany(targetEntity=SocialPost::class, mappedBy="outfit", cascade={"persist"})
     */
    private $socialPosts;
}
```
- **Stratégie** :
  - CASCADE REMOVE pour OutfitItem
  - PAS DE CASCADE pour SocialPost
- **Raison** :
  - Les items de tenue n'ont pas de sens sans la tenue
  - Les posts sociaux doivent être préservés comme historique

### 4. Système Social
```php
class SocialPost
{
    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="post", cascade={"remove"})
     */
    private $likes;
}

class Comment
{
    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="parentComment", cascade={"remove"})
     */
    private $replies;

    /**
     * @ORM\OneToMany(targetEntity=CommentLike::class, mappedBy="comment", cascade={"remove"})
     */
    private $likes;
}
```
- **Stratégie** : CASCADE COMPLET pour la hiérarchie sociale
- **Raison** : Maintien de la cohérence de l'arbre social

### 5. Système Partenaire
```php
class Partner
{
    /**
     * @ORM\OneToMany(targetEntity=PartnerProduct::class, mappedBy="partner", cascade={"remove"})
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=PartnerOrder::class, mappedBy="partner")
     */
    private $orders;
}
```
- **Stratégie** :
  - CASCADE pour les produits
  - PAS DE CASCADE pour les commandes
- **Raison** : Préservation de l'historique des commandes

### 6. Modération et Administration
```php
class UserReport
{
    /**
     * @ORM\OneToOne(targetEntity=ModeratorAction::class, mappedBy="report", cascade={"persist"})
     */
    private $moderatorAction;
}
```
- **Stratégie** : PAS DE CASCADE pour les actions administratives
- **Raison** : Conservation des traces d'audit

## Règles de Décision

Pour choisir la stratégie de cascade appropriée, suivre ces règles :

1. **CASCADE COMPLET si** :
   - L'entité enfant n'a pas de sens sans le parent
   - La suppression du parent doit nécessairement entraîner celle de l'enfant
   - Exemple : User → Profile

2. **CASCADE REMOVE si** :
   - L'entité enfant est une dépendance technique
   - L'orphelin créerait une incohérence
   - Exemple : Outfit → OutfitItem

3. **PAS DE CASCADE si** :
   - L'entité enfant a une valeur historique
   - L'entité enfant peut exister indépendamment
   - Exemple : Partner → PartnerOrder
