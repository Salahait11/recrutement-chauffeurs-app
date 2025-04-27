# Recrutement Chauffeurs App

Application web complète pour la gestion du recrutement, des employés, des absences, des entretiens et de l'administration RH, dédiée aux sociétés de transport ou agences de chauffeurs.

---

## 🚀 Fonctionnalités principales
- Gestion des candidats, offres d'emploi, entretiens et évaluations
- Gestion des employés, absences, congés et demandes de congé
- Tests de conduite et évaluations associées
- Gestion documentaire (documents liés aux candidats/employés)
- Gestion des véhicules
- Génération de rapports PDF (offres, employés, absences, entretiens, etc.)
- Export CSV des employés
- Tableau de bord interactif avec statistiques et calendrier (Chart.js, FullCalendar)
- Gestion fine des rôles et permissions (Spatie Laravel Permission)

---

## 🛠️ Stack technique
- **Backend** : Laravel 12 (PHP 8.2+)
- **Frontend** : TailwindCSS, Vite, Alpine.js, Chart.js, FullCalendar, Heroicons, HeadlessUI
- **PDF** : DOMPDF (barryvdh/laravel-dompdf)
- **Permissions** : Spatie Laravel Permission
- **Tests** : PHPUnit, Laravel Dusk
- **Autres** : Axios, Faker, Mockery, Laravel Breeze (authentification)

---

## ⚡ Installation & configuration

### Prérequis
- PHP 8.2+
- Composer
- Node.js & npm
- Base de données MySQL ou SQLite

### Étapes
1. Cloner le dépôt :
   ```bash
   git clone <repo_url>
   cd recrutement-chauffeurs-app
   ```
2. Installer les dépendances backend et frontend :
   ```bash
   composer install
   npm install
   ```
3. Copier le fichier d'environnement :
   ```bash
   cp .env.example .env
   ```
4. Générer la clé d'application :
   ```bash
   php artisan key:generate
   ```
5. Configurer la base de données dans `.env` (MySQL ou SQLite)
6. Lancer les migrations et seeders :
   ```bash
   php artisan migrate --seed
   ```
7. Lancer le serveur de développement :
   ```bash
   npm run dev
   php artisan serve
   ```

---

## 📁 Structure du projet
- `app/Models` : Modèles Eloquent (Candidat, Employé, Offre, etc.)
- `app/Http/Controllers` : Contrôleurs métiers (CandidateController, EmployeeController, etc.)
- `app/Http/Requests` : Validation des formulaires
- `app/Policies` : Policies pour la gestion des permissions
- `resources/views` : Vues Blade (UI)
- `resources/js` : Scripts front-end (Alpine.js, Chart.js)
- `routes/web.php` : Définition des routes principales
- `config/permission.php` : Configuration Spatie Permission
- `tests/` : Tests unitaires et E2E

---

## 🔒 Sécurité & Permissions
- Authentification via Laravel Breeze
- Rôles et permissions dynamiques via Spatie Laravel Permission
- Middleware `auth`, `verified`, `role:admin` pour protéger les routes sensibles
- Policies pour contrôler l'accès aux opérations critiques

---

## 🧪 Tests
- Lancer tous les tests unitaires et fonctionnels :
  ```bash
  php artisan test
  ```
- Lancer les tests E2E avec Laravel Dusk :
  ```bash
  php artisan dusk
  ```

---

## 📝 Génération de rapports & export
- Génération de PDF (offres, employés, absences, entretiens...)
- Export CSV des employés
- Accès via les routes `/reports`, `/employees/pdf`, `/offers/pdf`, etc.

---

## 🤝 Contribution
1. Forker le projet
2. Créer une branche (`feature/ma-fonctionnalite`)
3. Commiter vos modifications
4. Ouvrir une pull request

---

## 📄 Documentation avancée
- Voir `rapport_pfe.md` pour les choix d'architecture, les explications métiers et techniques détaillées.
- La documentation métier et technique complète est maintenue dans ce fichier pour faciliter la compréhension et la maintenance du projet.

---

## 📧 Support & contact
Pour toute question ou bug, ouvrir une issue GitHub ou contacter l'équipe projet.

---

© 2025 Recrutement Chauffeurs App. Tous droits réservés.
