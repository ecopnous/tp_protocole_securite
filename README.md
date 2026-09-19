# 🔐 Application Web Sécurisée — Laravel

<p align="center">
  <strong>Application web avec authentification renforcée, MFA/2FA et gestion sécurisée des accès</strong>
</p>

---

## 📋 Présentation du projet

Ce projet consiste en la conception et le développement d'une application web sécurisée basée sur le framework **Laravel**.

L'application met l'accent sur la sécurité des utilisateurs et des données à travers plusieurs mécanismes :

- Authentification des utilisateurs
- Authentification multifacteur (MFA)
- Authentification à deux facteurs (2FA)
- Protection des routes
- Gestion des sessions
- Contrôle des accès
- Validation des données
- Protection contre les requêtes CSRF
- Hachage sécurisé des mots de passe
- Gestion des erreurs
- Base de données SQLite
- Déploiement sur Hostinger

---

# 👥 Membres du groupe

| Membre | Rôle |
|---|---|
| **Ecopnous Banzuzi** |
| **Ariel Tshibanda**  |
| **Symphorien Tshimanga** |
---

# 🎯 Objectifs du projet

## Objectif général

Développer une application web moderne et sécurisée permettant de gérer des utilisateurs et des ressources tout en garantissant la confidentialité, l'intégrité et la disponibilité des données.

## Objectifs spécifiques

Le projet vise notamment à :

- Mettre en place une authentification sécurisée.
- Protéger les comptes utilisateurs contre les accès non autorisés.
- Implémenter un mécanisme MFA/2FA.
- Sécuriser les routes sensibles de l'application.
- Contrôler les permissions et les accès.
- Protéger les formulaires contre les attaques CSRF.
- Valider et filtrer les données entrantes.
- Sécuriser les mots de passe.
- Utiliser une base de données SQLite.
- Déployer l'application sur un hébergement Hostinger.
- Tester les principales fonctionnalités de l'application.
- Documenter l'architecture et les mécanismes de sécurité.

---

# 🛠️ Technologies utilisées

## Backend

- **Laravel**
- PHP
- Eloquent ORM
- Blade
- Laravel Middleware
- Laravel Authentication

## Base de données

- **SQLite**
- Migrations Laravel
- Eloquent ORM

## Frontend

- HTML5
- CSS3
- JavaScript
- Blade Templates

## Sécurité

- Authentification
- MFA
- 2FA
- Middleware
- CSRF Protection
- Password Hashing
- Validation des requêtes
- Protection des routes
- Gestion des sessions
- Contrôle d'accès

## Déploiement

- **Hostinger**
- PHP
- SSH
- Composer
- Git

---

# 🏗️ Architecture du projet

L'application suit l'architecture **MVC (Model - View - Controller)** proposée par Laravel.

```text
                    ┌──────────────────────┐
                    │       UTILISATEUR    │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │       NAVIGATEUR     │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │        ROUTES        │
                    │   web.php / api.php  │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │     MIDDLEWARE       │
                    │ Auth / MFA / CSRF     │
                    │ Permissions / Access │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │     CONTROLLERS      │
                    └──────────┬───────────┘
                               │
                  ┌────────────┴────────────┐
                  ▼                         ▼
        ┌──────────────────┐      ┌──────────────────┐
        │      MODELS      │      │      SERVICES    │
        │    Eloquent ORM  │      │ Business Logic   │
        └────────┬─────────┘      └──────────────────┘
                 │
                 ▼
        ┌──────────────────┐
        │     SQLite       │
        │ database.sqlite  │
        └──────────────────┘
