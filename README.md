# Système de Gestion de Recrutement de Chauffeurs

## Description
Système de gestion complet pour le recrutement et la gestion des chauffeurs, incluant la gestion des candidatures, des employés, des absences et la génération de rapports.

## Documentation

### Guides Utilisateur
- [Guide Administrateur](docs/GUIDE_ADMIN.md) - Documentation complète pour les administrateurs
- [Guide Utilisateur](docs/GUIDE_UTILISATEUR.md) - Documentation pour tous les utilisateurs
- [Guide Technique](docs/GUIDE_TECHNIQUE.md) - Documentation technique pour les développeurs
- [Guide de Sécurité](docs/GUIDE_SECURITE.md) - Documentation sur la sécurité et la protection des données
- [Guide de Maintenance](docs/GUIDE_MAINTENANCE.md) - Documentation sur la maintenance du système

## Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Node.js et npm

### Étapes d'Installation
1. Cloner le dépôt
2. Installer les dépendances PHP : `composer install`
3. Installer les dépendances JavaScript : `npm install`
4. Copier le fichier .env : `cp .env.example .env`
5. Configurer la base de données dans .env
6. Générer la clé d'application : `php artisan key:generate`
7. Exécuter les migrations : `php artisan migrate`
8. Compiler les assets : `npm run build`

## Fonctionnalités

### Gestion des Candidats
- Création et suivi des candidatures
- Gestion des documents
- Évaluation des compétences
- Planification des entretiens

### Gestion des Employés
- Profils détaillés
- Gestion des documents
- Suivi des performances
- Gestion des absences

### Rapports et Statistiques
- Tableaux de bord personnalisés
- Export de données
- Génération de PDF
- Analyses avancées

## Sécurité
- Authentification sécurisée
- Gestion des rôles et permissions
- Protection des données sensibles
- Conformité RGPD

## Maintenance
- Procédures de sauvegarde
- Mises à jour automatiques
- Monitoring du système
- Support technique

## Licence
Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## Support
Pour toute question ou assistance :
- Consultez la documentation
- Contactez le support technique
- Ouvrez un ticket sur le système de support

## Contribution
Les contributions sont les bienvenues ! Veuillez consulter les [directives de contribution](CONTRIBUTING.md) pour plus d'informations.

## Table des Matières
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Structure du Projet](#structure-du-projet)
4. [Fonctionnalités](#fonctionnalités)
5. [Guide d'Utilisation](#guide-dutilisation)
6. [Technologies Utilisées](#technologies-utilisées)
7. [Sécurité](#sécurité)
8. [Dépannage](#dépannage)

## Introduction

Ce projet est un système de gestion de recrutement de chauffeurs développé avec Laravel. Il permet de gérer le processus complet de recrutement, depuis la candidature jusqu'à l'embauche, ainsi que la gestion des employés et de leurs absences.

### Objectifs
- Gérer le processus de recrutement des chauffeurs
- Suivre les candidatures et les entretiens
- Gérer les employés et leurs documents
- Suivre les absences et les justifications
- Générer des rapports PDF

## Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Node.js et NPM

### Étapes d'Installation

1. Cloner le projet
```bash
git clone [url-du-projet]
cd recrutement-chauffeurs-app
```

2. Installer les dépendances PHP
```bash
composer install
```

3. Installer les dépendances JavaScript
```bash
npm install
```

4. Copier le fichier .env
```bash
cp .env.example .env
```

5. Configurer la base de données dans .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_la_base
DB_USERNAME=utilisateur
DB_PASSWORD=mot_de_passe
```

6. Générer la clé d'application
```bash
php artisan key:generate
```

7. Exécuter les migrations
```bash
php artisan migrate
```

8. Lancer le serveur
```bash
php artisan serve
```

## Structure du Projet

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Auth/
│   │   └── ...
│   └── Middleware/
├── Models/
├── Services/
└── ...

resources/
├── views/
│   ├── admin/
│   ├── auth/
│   └── ...
└── ...

routes/
├── web.php
└── ...

database/
├── migrations/
└── ...
```

## Fonctionnalités

### 1. Gestion des Utilisateurs
- Création et gestion des comptes utilisateurs
- Attribution des rôles (admin, manager, employee, candidate)
- Interface d'administration des utilisateurs
- Filtrage et recherche des utilisateurs

### 2. Gestion des Candidats
- Suivi des candidatures
- Gestion des entretiens
- Tests de conduite
- Processus de recrutement

### 3. Gestion des Employés
- Création et modification des profils
- Gestion des documents
- Suivi des informations professionnelles
- Génération de PDF

### 4. Gestion des Absences
- Enregistrement des absences
- Justification des absences
- Suivi des congés
- Génération de rapports

## Guide d'Utilisation

### Connexion
1. Accédez à `/login`
2. Entrez vos identifiants
3. Selon votre rôle, vous aurez accès à différentes fonctionnalités

### Administration
1. Accédez au tableau de bord
2. Utilisez le menu de navigation
3. Gérez les utilisateurs, candidats, employés et absences

### Gestion des Documents
1. Accédez à la section Documents
2. Téléchargez ou consultez les documents
3. Générez des PDF si nécessaire

## Technologies Utilisées

- **Backend**
  - Laravel 10.x
  - PHP 8.1+
  - MySQL

- **Frontend**
  - Tailwind CSS
  - JavaScript
  - Blade Templates

- **Outils**
  - Composer
  - NPM
  - Git

## Sécurité

### Mesures de Sécurité Implémentées
- Authentification sécurisée
- Protection CSRF
- Validation des données
- Gestion des rôles et permissions
- Protection des routes

### Bonnes Pratiques
- Utilisation de mots de passe forts
- Limitation des tentatives de connexion
- Validation des entrées utilisateur
- Protection des fichiers sensibles

## Dépannage

### Problèmes Courants

1. **Erreur de Connexion à la Base de Données**
   - Vérifier les paramètres dans .env
   - S'assurer que MySQL est en cours d'exécution

2. **Problèmes de Permissions**
   - Vérifier les permissions des dossiers storage et bootstrap/cache
   - Exécuter `php artisan storage:link`

3. **Erreurs 404**
   - Vérifier les routes dans routes/web.php
   - S'assurer que les contrôleurs existent

### Contact Support
Pour toute assistance supplémentaire, contactez l'administrateur système.

## Licence

Ce projet est sous licence [MIT](LICENSE).
