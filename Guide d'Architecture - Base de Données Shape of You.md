---


---

<h1 id="guide-darchitecture---base-de-données-shape-of-you">Guide d’Architecture - Base de Données Shape of You</h1>
<h2 id="vue-densemble">Vue d’ensemble</h2>
<p>Shape of You est une application de gestion de garde-robe avec des fonctionnalités sociales et e-commerce. L’architecture de la base de données est conçue pour supporter une application évolutive avec de multiples domaines interconnectés.</p>
<h2 id="domaines-fonctionnels">Domaines Fonctionnels</h2>
<h3 id="système-utilisateur">1. Système Utilisateur</h3>
<h4 id="tables-principales--user-profile">Tables principales : User, Profile</h4>
<pre class=" language-sql"><code class="prism  language-sql"><span class="token keyword">User</span> <span class="token punctuation">(</span><span class="token number">1</span><span class="token punctuation">)</span> <span class="token comment">--- (1) Profile  // One-to-One</span>
</code></pre>
<ul>
<li><strong>Objectif</strong> : Gestion des utilisateurs et de leurs préférences</li>
<li><strong>Points clés</strong> :
<ul>
<li>Séparation claire entre authentification (User) et préférences (Profile)</li>
<li>Stockage JSON pour les préférences flexibles</li>
<li>Timestamps pour l’audit et le suivi</li>
</ul>
</li>
</ul>
<h3 id="système-de-garde-robe">2. Système de Garde-robe</h3>
<h4 id="tables-principales--wardrobeitem-category-tag-acquisition">Tables principales : WardrobeItem, Category, Tag, Acquisition</h4>
<pre class=" language-sql"><code class="prism  language-sql">WardrobeItem <span class="token punctuation">(</span>N<span class="token punctuation">)</span> <span class="token comment">--- (1) Category  // Many-to-One</span>
WardrobeItem <span class="token punctuation">(</span>N<span class="token punctuation">)</span> <span class="token comment">--- (N) Tag       // Many-to-Many via WardrobeItemTag</span>
WardrobeItem <span class="token punctuation">(</span><span class="token number">1</span><span class="token punctuation">)</span> <span class="token comment">--- (1) Acquisition  // One-to-One</span>
</code></pre>
<ul>
<li><strong>Objectif</strong> : Gestion des vêtements et leur catégorisation</li>
<li><strong>Fonctionnalités clés</strong> :
<ul>
<li>Catégorisation hiérarchique (auto-référencement Category)</li>
<li>Système de tags flexible</li>
<li>Tracking des acquisitions</li>
</ul>
</li>
</ul>
<h3 id="système-de-tenues">3. Système de Tenues</h3>
<h4 id="tables-principales--outfit-outfititem">Tables principales : Outfit, OutfitItem</h4>
<pre class=" language-sql"><code class="prism  language-sql">Outfit <span class="token punctuation">(</span>N<span class="token punctuation">)</span> <span class="token comment">--- (N) WardrobeItem  // Many-to-Many via OutfitItem</span>
</code></pre>
<ul>
<li><strong>Objectif</strong> : Création et gestion des tenues</li>
<li><strong>Points techniques</strong> :
<ul>
<li>OutfitItem comme table de jonction avec position</li>
<li>Support des tenues publiques/privées</li>
<li>Association avec les posts sociaux</li>
</ul>
</li>
</ul>
<h3 id="système-social">4. Système Social</h3>
<h4 id="tables-principales--socialpost-comment-like-follow">Tables principales : SocialPost, Comment, Like, Follow</h4>
<pre class=" language-sql"><code class="prism  language-sql">SocialPost <span class="token punctuation">(</span><span class="token number">1</span><span class="token punctuation">)</span> <span class="token comment">--- (N) Comment</span>
<span class="token keyword">Comment</span> <span class="token punctuation">(</span><span class="token number">1</span><span class="token punctuation">)</span> <span class="token comment">--- (N) Comment      // Self-referencing</span>
<span class="token keyword">User</span> <span class="token punctuation">(</span>N<span class="token punctuation">)</span> <span class="token comment">--- (N) User           // Follow relationship</span>
</code></pre>
<ul>
<li><strong>Objectif</strong> : Fonctionnalités sociales et engagement</li>
<li><strong>Caractéristiques</strong> :
<ul>
<li>Commentaires imbriqués</li>
<li>Système de suivi</li>
<li>Compteurs de performance (likes, comments)</li>
</ul>
</li>
</ul>
<h3 id="système-partenaire">5. Système Partenaire</h3>
<h4 id="tables-principales--partner-partnerproduct-partnerorder">Tables principales : Partner, PartnerProduct, PartnerOrder</h4>
<pre class=" language-sql"><code class="prism  language-sql">Partner <span class="token punctuation">(</span><span class="token number">1</span><span class="token punctuation">)</span> <span class="token comment">--- (N) PartnerProduct</span>
Partner <span class="token punctuation">(</span><span class="token number">1</span><span class="token punctuation">)</span> <span class="token comment">--- (N) PartnerOrder</span>
WardrobeItem <span class="token punctuation">(</span>N<span class="token punctuation">)</span> <span class="token comment">--- (N) PartnerProduct  // via WardrobeItemPartnerProduct</span>
</code></pre>
<ul>
<li><strong>Objectif</strong> : Intégration e-commerce et monétisation</li>
<li><strong>Fonctionnalités</strong> :
<ul>
<li>Gestion des produits partenaires</li>
<li>Tracking des commandes</li>
<li>Système de commission</li>
</ul>
</li>
</ul>
<h3 id="système-dadministration">6. Système d’Administration</h3>
<h4 id="tables-principales--adminlog-statistics-userreport-moderatoraction">Tables principales : AdminLog, Statistics, UserReport, ModeratorAction</h4>
<pre class=" language-sql"><code class="prism  language-sql">UserReport <span class="token punctuation">(</span><span class="token number">1</span><span class="token punctuation">)</span> <span class="token comment">--- (1) ModeratorAction</span>
ModeratorAction <span class="token punctuation">(</span>N<span class="token punctuation">)</span> <span class="token comment">--- (1) User</span>
</code></pre>
<ul>
<li><strong>Objectif</strong> : Gestion et modération de l’application</li>
<li><strong>Composants clés</strong> :
<ul>
<li>Logs d’audit</li>
<li>Statistiques agrégées</li>
<li>Workflow de modération</li>
</ul>
</li>
</ul>
<h3 id="système-ia">7. Système IA</h3>
<h4 id="tables-principales--aianalysis-aialert">Tables principales : AIAnalysis, AIAlert</h4>
<pre class=" language-sql"><code class="prism  language-sql">AIAnalysis <span class="token punctuation">(</span>N<span class="token punctuation">)</span> <span class="token comment">--- (1) WardrobeItem</span>
AIAnalysis <span class="token punctuation">(</span>N<span class="token punctuation">)</span> <span class="token comment">--- (1) Outfit</span>
</code></pre>
<ul>
<li><strong>Objectif</strong> : Intégration des fonctionnalités IA</li>
<li><strong>Caractéristiques</strong> :
<ul>
<li>Analyse des items et tenues</li>
<li>Système d’alerte pour modération</li>
<li>Stockage des résultats d’analyse</li>
</ul>
</li>
</ul>
<h2 id="considérations-techniques">Considérations Techniques</h2>
<h3 id="indexation">1. Indexation</h3>
<pre class=" language-sql"><code class="prism  language-sql"><span class="token comment">-- Indexes critiques</span>
<span class="token keyword">CREATE</span> <span class="token keyword">INDEX</span> idx_user_created <span class="token keyword">ON</span> users<span class="token punctuation">(</span>user_id<span class="token punctuation">,</span> created_at<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token keyword">CREATE</span> <span class="token keyword">INDEX</span> idx_outfit_items <span class="token keyword">ON</span> outfit_items<span class="token punctuation">(</span>outfit_id<span class="token punctuation">,</span> wardrobe_item_id<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token keyword">CREATE</span> <span class="token keyword">INDEX</span> idx_content_moderation <span class="token keyword">ON</span> content_moderation<span class="token punctuation">(</span>content_type<span class="token punctuation">,</span> content_id<span class="token punctuation">)</span><span class="token punctuation">;</span>
</code></pre>
<h3 id="triggers-recommandés">2. Triggers Recommandés</h3>
<ul>
<li>Mise à jour automatique des compteurs (likes_count, comments_count)</li>
<li>Mise à jour des timestamps updated_at</li>
<li>Validation des données sensibles</li>
</ul>
<h3 id="gestion-des-relations">3. Gestion des Relations</h3>
<ul>
<li>Utilisation systématique de contraintes de clé étrangère</li>
<li>Cascade DELETE configurée selon le contexte</li>
<li>Indexes sur les clés étrangères fréquemment utilisées</li>
</ul>
<h3 id="optimisations-de-performance">4. Optimisations de Performance</h3>
<ul>
<li>Compteurs précalculés pour les métriques fréquentes</li>
<li>Dénormalisation stratégique (ex: nested comments level)</li>
<li>Partitionnement possible sur les grandes tables (logs, posts)</li>
</ul>

