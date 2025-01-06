# Projet Symfony : Shape Of You

Bienvenue dans le **Projet Symfony : Nom de votre projet**. Ce document explique comment configurer, installer et lancer l'application, ainsi que les outils nécessaires pour contribuer ou explorer davantage.

---

## Table des matières

- [About](#About)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Start the project](#Start-the-project)
- [Useful commands](#Useful-commands)
- [Contribution](#contribution)
- [Licence](#licence)

---

## About

Ce projet est une application web développée en Symfony. Elle inclut une architecture moderne avec des fonctionnalités interactives grâce à Webpack Encore et Docker.

---

## Prérequis

Avant de commencer, assurez-vous que les outils suivants sont installés sur votre machine :

- **PHP** (>= 8.1)
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js** (>= 14.x) et **npm**
- **Docker** et **Docker Compose**
- **Symfony CLI**

---

## Installation

### 1. Clone the project

Clonez le dépôt Git et naviguez dans le dossier du projet :

```bash
git clone https://github.com/thierrymgn/shape-of-you.git
cd shape-of-you
```

### 2. Dependance Install

```bash
composer install
npm install
```

---

## Start the project

### 1. Start the docker

```bash
docker compose up -d
```
### 2. Symfony Part

```bash
symfony serve
```

### 3. Webpack encore Part

```bash
npm run dev
```

---

## Contribution

- @Zeyoman
- @thierrymgn
- @EkinL

---

## License