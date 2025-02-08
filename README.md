Bienvenue dans le projet **Shape Of You** !

Shape Of You est une application mobile-first conçue pour aider les utilisateurs à mieux s'habiller en fonction de leur morphologie, des tendances actuelles et des conseils de mode. L'application propose également des fonctionnalités autour de la mode durable telles que la seconde main, l'éco-conception, l'upcycling, la couture et la personnalisation.

---

## 📌 **Table des matières**

- [📝 À propos](#-À-propos)
- [📦 Prérequis](#-Prérequis)
- [⚙️ Installation](#-Installation)
- [🚀 Lancement du projet](#-Lancement-du-projet)
- [🤝 Contribution](#-Contribution)
- [📜 Licence](#-Licence)

---

## 📝 **À propos**

Ce projet est une application web développée en **Symfony**, intégrant **Webpack Encore** pour la gestion des assets et utilisant **Docker** pour simplifier l'environnement de développement.

L'architecture repose sur des technologies modernes pour offrir une expérience utilisateur fluide et interactive.

---

## 📦 **Prérequis**

Avant de commencer, assurez-vous d'avoir les outils suivants installés sur votre machine :

- **PHP** (>= 8.1)
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js** (>= 14.x) et **npm**
- **Docker** et **Docker Compose**
- **Symfony CLI**

---

## ⚙️ **Installation**

### 1️⃣ **Cloner le projet**

Clonez le dépôt Git et accédez au dossier du projet :

```bash
git clone https://github.com/thierrymgn/shape-of-you.git
cd shape-of-you
```

### 2️⃣ **Installer les dépendances PHP et JavaScript**

Installez les dépendances PHP :

```bash
composer install
```

Installez les dépendances JavaScript :

```bash
npm install
```

### 3️⃣ **Configurer les variables d'environnement**

Copiez le fichier `.env` et ajustez les configurations si nécessaire :

```bash
cp .env.example .env
```

Si besoin, modifiez le fichier `.env` pour configurer la base de données, par exemple :

```env
DATABASE_URL=mysql://user:password@127.0.0.1:3306/shape_of_you
```

---

## 🚀 **Lancement du projet**

### 1️⃣ **Démarrer l’environnement Docker**

Lancez les conteneurs nécessaires :

```bash
docker compose up -d
```

### 2️⃣ **Démarrer le serveur Symfony**

```bash
symfony serve
```

### 3️⃣ **Lancer Webpack Encore pour la compilation des assets**

```bash
npm run watch
```

Votre application est maintenant accessible à l'adresse :  
➡️ **http://127.0.0.1:8000**

---

## 🤝 **Contribution**

Les contributeurs de ce projet :

- @Zeyoman
- @thierrymgn
- @EkinL

---

## 📜 **Licence**

**MIT License**  
\
© 2025 **Thierry MGN, Zeyoman, EkinL**
