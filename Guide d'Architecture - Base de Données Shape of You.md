---


---

<h1 id="routes-api---shape-of-you">Routes API - Shape of You</h1>
<h2 id="🔐-authentication--user-management">🔐 Authentication &amp; User Management</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Authentication
POST    /api/auth/register                  # Inscription
POST    /api/auth/login                     # Connexion
POST    /api/auth/logout                    # Déconnexion
POST    /api/auth/refresh-token             # Rafraîchir le token
POST    /api/auth/forgot-password           # Mot de passe oublié
POST    /api/auth/reset-password            # Réinitialisation du mot de passe

# Profile
GET     /api/user/profile                   # Obtenir son profil
PUT     /api/user/profile                   # Mettre à jour son profil
PUT     /api/user/preferences               # Mettre à jour ses préférences
GET     /api/user/statistics                # Statistiques personnelles
</code></pre>
<h2 id="👕-wardrobe-management">👕 Wardrobe Management</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Categories
GET     /api/categories                     # Liste des catégories
GET     /api/categories/{id}                # Détail d'une catégorie
GET     /api/categories/{id}/subcategories  # Sous-catégories

# Wardrobe Items
GET     /api/wardrobe                       # Liste de sa garde-robe
POST    /api/wardrobe                       # Ajouter un vêtement
GET     /api/wardrobe/{id}                  # Détail d'un vêtement
PUT     /api/wardrobe/{id}                  # Modifier un vêtement
DELETE  /api/wardrobe/{id}                  # Supprimer un vêtement
POST    /api/wardrobe/scan                  # Scanner un vêtement (IA)
GET     /api/wardrobe/search               # Recherche dans la garde-robe

# Tags
GET     /api/tags                           # Liste des tags
POST    /api/wardrobe/{id}/tags            # Ajouter des tags
DELETE  /api/wardrobe/{id}/tags/{tagId}    # Retirer un tag

# Acquisitions
GET     /api/wardrobe/{id}/acquisition      # Info d'acquisition
POST    /api/wardrobe/{id}/acquisition      # Ajouter info d'acquisition
PUT     /api/wardrobe/{id}/acquisition      # Modifier info d'acquisition
</code></pre>
<h2 id="👗-outfit-management">👗 Outfit Management</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Outfits
GET     /api/outfits                        # Liste des tenues
POST    /api/outfits                        # Créer une tenue
GET     /api/outfits/{id}                   # Détail d'une tenue
PUT     /api/outfits/{id}                   # Modifier une tenue
DELETE  /api/outfits/{id}                   # Supprimer une tenue
POST    /api/outfits/{id}/items            # Ajouter des items à la tenue
DELETE  /api/outfits/{id}/items/{itemId}   # Retirer un item
PUT     /api/outfits/{id}/items/reorder    # Réorganiser les items
GET     /api/outfits/suggestions           # Suggestions de tenues (IA)
</code></pre>
<h2 id="🤝-social-features">🤝 Social Features</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Posts
GET     /api/social/feed                    # Flux social
POST    /api/social/posts                   # Créer un post
GET     /api/social/posts/{id}              # Détail d'un post
PUT     /api/social/posts/{id}              # Modifier un post
DELETE  /api/social/posts/{id}              # Supprimer un post

# Comments
GET     /api/posts/{id}/comments            # Commentaires d'un post
POST    /api/posts/{id}/comments            # Commenter
PUT     /api/comments/{id}                  # Modifier un commentaire
DELETE  /api/comments/{id}                  # Supprimer un commentaire
POST    /api/comments/{id}/reply            # Répondre à un commentaire

# Likes
POST    /api/posts/{id}/like                # Liker un post
DELETE  /api/posts/{id}/like                # Unliker un post
POST    /api/comments/{id}/like             # Liker un commentaire
DELETE  /api/comments/{id}/like             # Unliker un commentaire

# Following
GET     /api/social/users                   # Liste des utilisateurs
POST    /api/social/users/{id}/follow       # Suivre un utilisateur
DELETE  /api/social/users/{id}/follow       # Ne plus suivre
GET     /api/social/users/{id}/followers    # Liste des followers
GET     /api/social/users/{id}/following    # Liste des followings
</code></pre>
<h2 id="🛍️-partner--shopping">🛍️ Partner &amp; Shopping</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Products
GET     /api/products                       # Catalogue produits
GET     /api/products/{id}                  # Détail produit
GET     /api/products/similar/{itemId}      # Produits similaires
GET     /api/products/recommendations       # Recommandations

# Partners
GET     /api/partners                       # Liste des partenaires
GET     /api/partners/{id}/products         # Produits d'un partenaire

# Orders
GET     /api/orders                         # Historique commandes
POST    /api/orders                         # Passer commande
GET     /api/orders/{id}                    # Détail commande
</code></pre>
<h2 id="🤖-ai-features">🤖 AI Features</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext">POST    /api/ai/analyze-image               # Analyser une image
POST    /api/ai/detect-items                # Détecter les items
POST    /api/ai/suggest-outfit              # Suggérer une tenue
GET     /api/ai/trends                      # Analyse des tendances
</code></pre>
<h2 id="📱-notifications">📱 Notifications</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext">GET     /api/notifications                  # Liste notifications
PUT     /api/notifications/{id}/read        # Marquer comme lu
PUT     /api/notifications/read-all         # Tout marquer comme lu
GET     /api/notifications/settings         # Préférences notifs
PUT     /api/notifications/settings         # Modifier préférences
</code></pre>
<h2 id="👮‍♂️-administration">👮‍♂️ Administration</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Dashboard
GET     /api/admin/dashboard                # Vue d'ensemble
GET     /api/admin/statistics               # Statistiques globales
GET     /api/admin/logs                     # Logs d'activité

# Moderation
GET     /api/admin/reports                  # Liste signalements
PUT     /api/admin/reports/{id}             # Traiter signalement
GET     /api/admin/moderation-queue         # File de modération
POST    /api/admin/moderate/{id}            # Action de modération

# Users Management
GET     /api/admin/users                    # Liste utilisateurs
PUT     /api/admin/users/{id}/ban           # Bannir utilisateur
PUT     /api/admin/users/{id}/role          # Modifier rôle

# Content Management
GET     /api/admin/categories               # Gérer catégories
POST    /api/admin/categories               # Ajouter catégorie
PUT     /api/admin/categories/{id}          # Modifier catégorie
DELETE  /api/admin/categories/{id}          # Supprimer catégorie

# Partners Management
GET     /api/admin/partners                 # Liste partenaires
POST    /api/admin/partners                 # Ajouter partenaire
PUT     /api/admin/partners/{id}            # Modifier partenaire
GET     /api/admin/partners/sales           # Rapport ventes
</code></pre>
<h2 id="points-importants-">Points importants :</h2>
<ol>
<li>
<p><strong>Sécurité</strong> :</p>
<ul>
<li>Protection CSRF pour les routes non-API</li>
<li>Authentification JWT pour l’API</li>
<li>Vérification des rôles pour routes admin</li>
</ul>
</li>
<li>
<p><strong>Versions</strong> :</p>
<ul>
<li>Préfixe <code>/api/v1</code> possible pour le versioning</li>
<li>Documentation OpenAPI/Swagger à prévoir</li>
</ul>
</li>
<li>
<p><strong>Pagination</strong> :</p>
<ul>
<li>Toutes les routes GET de liste supportent :</li>
<li><code>?page=</code> et <code>?limit=</code></li>
<li><code>?sort=</code> et <code>?order=</code></li>
</ul>
</li>
<li>
<p><strong>Filtres communs</strong> :</p>
<ul>
<li><code>?search=</code> pour la recherche</li>
<li><code>?category=</code> pour le filtrage</li>
<li><code>?start_date=</code> et <code>?end_date=</code># Routes API - Shape of You</li>
</ul>
</li>
</ol>
<h2 id="🔐-authentication--user-management-1">🔐 Authentication &amp; User Management</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Authentication
POST    /api/auth/register                  # Inscription
POST    /api/auth/login                     # Connexion
POST    /api/auth/logout                    # Déconnexion
POST    /api/auth/refresh-token             # Rafraîchir le token
POST    /api/auth/forgot-password           # Mot de passe oublié
POST    /api/auth/reset-password            # Réinitialisation du mot de passe

# Profile
GET     /api/user/profile                   # Obtenir son profil
PUT     /api/user/profile                   # Mettre à jour son profil
PUT     /api/user/preferences               # Mettre à jour ses préférences
GET     /api/user/statistics                # Statistiques personnelles
</code></pre>
<h2 id="👕-wardrobe-management-1">👕 Wardrobe Management</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Categories
GET     /api/categories                     # Liste des catégories
GET     /api/categories/{id}                # Détail d'une catégorie
GET     /api/categories/{id}/subcategories  # Sous-catégories

# Wardrobe Items
GET     /api/wardrobe                       # Liste de sa garde-robe
POST    /api/wardrobe                       # Ajouter un vêtement
GET     /api/wardrobe/{id}                  # Détail d'un vêtement
PUT     /api/wardrobe/{id}                  # Modifier un vêtement
DELETE  /api/wardrobe/{id}                  # Supprimer un vêtement
POST    /api/wardrobe/scan                  # Scanner un vêtement (IA)
GET     /api/wardrobe/search               # Recherche dans la garde-robe

# Tags
GET     /api/tags                           # Liste des tags
POST    /api/wardrobe/{id}/tags            # Ajouter des tags
DELETE  /api/wardrobe/{id}/tags/{tagId}    # Retirer un tag

# Acquisitions
GET     /api/wardrobe/{id}/acquisition      # Info d'acquisition
POST    /api/wardrobe/{id}/acquisition      # Ajouter info d'acquisition
PUT     /api/wardrobe/{id}/acquisition      # Modifier info d'acquisition
</code></pre>
<h2 id="👗-outfit-management-1">👗 Outfit Management</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Outfits
GET     /api/outfits                        # Liste des tenues
POST    /api/outfits                        # Créer une tenue
GET     /api/outfits/{id}                   # Détail d'une tenue
PUT     /api/outfits/{id}                   # Modifier une tenue
DELETE  /api/outfits/{id}                   # Supprimer une tenue
POST    /api/outfits/{id}/items            # Ajouter des items à la tenue
DELETE  /api/outfits/{id}/items/{itemId}   # Retirer un item
PUT     /api/outfits/{id}/items/reorder    # Réorganiser les items
GET     /api/outfits/suggestions           # Suggestions de tenues (IA)
</code></pre>
<h2 id="🤝-social-features-1">🤝 Social Features</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Posts
GET     /api/social/feed                    # Flux social
POST    /api/social/posts                   # Créer un post
GET     /api/social/posts/{id}              # Détail d'un post
PUT     /api/social/posts/{id}              # Modifier un post
DELETE  /api/social/posts/{id}              # Supprimer un post

# Comments
GET     /api/posts/{id}/comments            # Commentaires d'un post
POST    /api/posts/{id}/comments            # Commenter
PUT     /api/comments/{id}                  # Modifier un commentaire
DELETE  /api/comments/{id}                  # Supprimer un commentaire
POST    /api/comments/{id}/reply            # Répondre à un commentaire

# Likes
POST    /api/posts/{id}/like                # Liker un post
DELETE  /api/posts/{id}/like                # Unliker un post
POST    /api/comments/{id}/like             # Liker un commentaire
DELETE  /api/comments/{id}/like             # Unliker un commentaire

# Following
GET     /api/social/users                   # Liste des utilisateurs
POST    /api/social/users/{id}/follow       # Suivre un utilisateur
DELETE  /api/social/users/{id}/follow       # Ne plus suivre
GET     /api/social/users/{id}/followers    # Liste des followers
GET     /api/social/users/{id}/following    # Liste des followings
</code></pre>
<h2 id="🛍️-partner--shopping-1">🛍️ Partner &amp; Shopping</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Products
GET     /api/products                       # Catalogue produits
GET     /api/products/{id}                  # Détail produit
GET     /api/products/similar/{itemId}      # Produits similaires
GET     /api/products/recommendations       # Recommandations

# Partners
GET     /api/partners                       # Liste des partenaires
GET     /api/partners/{id}/products         # Produits d'un partenaire

# Orders
GET     /api/orders                         # Historique commandes
POST    /api/orders                         # Passer commande
GET     /api/orders/{id}                    # Détail commande
</code></pre>
<h2 id="🤖-ai-features-1">🤖 AI Features</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext">POST    /api/ai/analyze-image               # Analyser une image
POST    /api/ai/detect-items                # Détecter les items
POST    /api/ai/suggest-outfit              # Suggérer une tenue
GET     /api/ai/trends                      # Analyse des tendances
</code></pre>
<h2 id="📱-notifications-1">📱 Notifications</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext">GET     /api/notifications                  # Liste notifications
PUT     /api/notifications/{id}/read        # Marquer comme lu
PUT     /api/notifications/read-all         # Tout marquer comme lu
GET     /api/notifications/settings         # Préférences notifs
PUT     /api/notifications/settings         # Modifier préférences
</code></pre>
<h2 id="👮‍♂️-administration-1">👮‍♂️ Administration</h2>
<pre class=" language-plaintext"><code class="prism  language-plaintext"># Dashboard
GET     /api/admin/dashboard                # Vue d'ensemble
GET     /api/admin/statistics               # Statistiques globales
GET     /api/admin/logs                     # Logs d'activité

# Moderation
GET     /api/admin/reports                  # Liste signalements
PUT     /api/admin/reports/{id}             # Traiter signalement
GET     /api/admin/moderation-queue         # File de modération
POST    /api/admin/moderate/{id}            # Action de modération

# Users Management
GET     /api/admin/users                    # Liste utilisateurs
PUT     /api/admin/users/{id}/ban           # Bannir utilisateur
PUT     /api/admin/users/{id}/role          # Modifier rôle

# Content Management
GET     /api/admin/categories               # Gérer catégories
POST    /api/admin/categories               # Ajouter catégorie
PUT     /api/admin/categories/{id}          # Modifier catégorie
DELETE  /api/admin/categories/{id}          # Supprimer catégorie

# Partners Management
GET     /api/admin/partners                 # Liste partenaires
POST    /api/admin/partners                 # Ajouter partenaire
PUT     /api/admin/partners/{id}            # Modifier partenaire
GET     /api/admin/partners/sales           # Rapport ventes
</code></pre>
<h2 id="points-importants--1">Points importants :</h2>
<ol>
<li>
<p><strong>Sécurité</strong> :</p>
<ul>
<li>Protection CSRF pour les routes non-API</li>
<li>Authentification JWT pour l’API</li>
<li>Vérification des rôles pour routes admin</li>
</ul>
</li>
<li>
<p><strong>Versions</strong> :</p>
<ul>
<li>Préfixe <code>/api/v1</code> possible pour le versioning</li>
<li>Documentation OpenAPI/Swagger à prévoir</li>
</ul>
</li>
<li>
<p><strong>Pagination</strong> :</p>
<ul>
<li>Toutes les routes GET de liste supportent :</li>
<li><code>?page=</code> et <code>?limit=</code></li>
<li><code>?sort=</code> et <code>?order=</code></li>
</ul>
</li>
<li>
<p><strong>Filtres communs</strong> :</p>
<ul>
<li><code>?search=</code> pour la recherche</li>
<li><code>?category=</code> pour le filtrage</li>
<li><code>?start_date=</code> et <code>?end_date=</code></li>
</ul>
</li>
</ol>

