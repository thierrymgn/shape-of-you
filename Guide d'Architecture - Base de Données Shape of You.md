
# Guide d'Architecture - Base de Données Shape of You

## Vue d'ensemble
Shape of You est une application de gestion de garde-robe avec des fonctionnalités sociales et e-commerce. L'architecture de la base de données est conçue pour supporter une application évolutive avec de multiples domaines interconnectés.

## Domaines Fonctionnels

### 1. Système Utilisateur
#### Tables principales : User, Profile
```sql
User (1) --- (1) Profile  // One-to-One
```
- **Objectif** : Gestion des utilisateurs et de leurs préférences
- **Points clés** :
  - Séparation claire entre authentification (User) et préférences (Profile)
  - Stockage JSON pour les préférences flexibles
  - Timestamps pour l'audit et le suivi

### 2. Système de Garde-robe
#### Tables principales : WardrobeItem, Category, Tag, Acquisition
```sql
WardrobeItem (N) --- (1) Category  // Many-to-One
WardrobeItem (N) --- (N) Tag       // Many-to-Many via WardrobeItemTag
WardrobeItem (1) --- (1) Acquisition  // One-to-One
```
- **Objectif** : Gestion des vêtements et leur catégorisation
- **Fonctionnalités clés** :
  - Catégorisation hiérarchique (auto-référencement Category)
  - Système de tags flexible
  - Tracking des acquisitions

### 3. Système de Tenues
#### Tables principales : Outfit, OutfitItem
```sql
Outfit (N) --- (N) WardrobeItem  // Many-to-Many via OutfitItem
```
- **Objectif** : Création et gestion des tenues
- **Points techniques** :
  - OutfitItem comme table de jonction avec position
  - Support des tenues publiques/privées
  - Association avec les posts sociaux

### 4. Système Social
#### Tables principales : SocialPost, Comment, Like, Follow
```sql
SocialPost (1) --- (N) Comment
Comment (1) --- (N) Comment      // Self-referencing
User (N) --- (N) User           // Follow relationship
```
- **Objectif** : Fonctionnalités sociales et engagement
- **Caractéristiques** :
  - Commentaires imbriqués
  - Système de suivi
  - Compteurs de performance (likes, comments)

### 5. Système Partenaire
#### Tables principales : Partner, PartnerProduct, PartnerOrder
```sql
Partner (1) --- (N) PartnerProduct
Partner (1) --- (N) PartnerOrder
WardrobeItem (N) --- (N) PartnerProduct  // via WardrobeItemPartnerProduct
```
- **Objectif** : Intégration e-commerce et monétisation
- **Fonctionnalités** :
  - Gestion des produits partenaires
  - Tracking des commandes
  - Système de commission

### 6. Système d'Administration
#### Tables principales : AdminLog, Statistics, UserReport, ModeratorAction
```sql
UserReport (1) --- (1) ModeratorAction
ModeratorAction (N) --- (1) User
```
- **Objectif** : Gestion et modération de l'application
- **Composants clés** :
  - Logs d'audit
  - Statistiques agrégées
  - Workflow de modération

### 7. Système IA
#### Tables principales : AIAnalysis, AIAlert
```sql
AIAnalysis (N) --- (1) WardrobeItem
AIAnalysis (N) --- (1) Outfit
```
- **Objectif** : Intégration des fonctionnalités IA
- **Caractéristiques** :
  - Analyse des items et tenues
  - Système d'alerte pour modération
  - Stockage des résultats d'analyse

## Considérations Techniques

### 1. Indexation
```sql
-- Indexes critiques
CREATE INDEX idx_user_created ON users(user_id, created_at);
CREATE INDEX idx_outfit_items ON outfit_items(outfit_id, wardrobe_item_id);
CREATE INDEX idx_content_moderation ON content_moderation(content_type, content_id);
```

### 2. Triggers Recommandés
- Mise à jour automatique des compteurs (likes_count, comments_count)
- Mise à jour des timestamps updated_at
- Validation des données sensibles

### 3. Gestion des Relations
- Utilisation systématique de contraintes de clé étrangère
- Cascade DELETE configurée selon le contexte
- Indexes sur les clés étrangères fréquemment utilisées

### 4. Optimisations de Performance
- Compteurs précalculés pour les métriques fréquentes
- Dénormalisation stratégique (ex: nested comments level)
- Partitionnement possible sur les grandes tables (logs, posts)
