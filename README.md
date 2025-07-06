# Recrutement Chauffeurs App

Application web complète pour la gestion du recrutement, des employés, des absences, des entretiens et de l'administration RH, dédiée aux sociétés de transport ou agences de chauffeurs.

---

## 🚀 Fonctionnalités principales
- **Gestion des candidats** : Création, suivi, évaluation et conversion en employés
- **Gestion des employés** : Profils détaillés, calcul automatique des augmentations salariales (3 mois et 3 ans)
- **Gestion des absences** : Suivi des absences, congés et demandes de congé
- **Entretiens et évaluations** : Planification, évaluation et suivi des candidats
- **Tests de conduite** : Gestion des tests pratiques et évaluations associées
- **Gestion documentaire** : Documents liés aux candidats et employés
- **Gestion des véhicules** : Suivi de la flotte et disponibilité
- **Génération de rapports PDF** : 
  - PDF détaillé des employés (profil complet)
  - Offres d'emploi
  - Rapports d'absences
  - Rapports d'entretiens
- **Tableau de bord avancé** : Statistiques en temps réel, tendances mensuelles, vue d'ensemble complète
- **Calendrier interactif** : Gestion des événements, entretiens et formations
- **Gestion fine des rôles** : Admin et employé avec permissions appropriées

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

### 🔑 Accès par défaut
Après l'installation, vous pouvez vous connecter avec :
- **Email** : `admin@example.com`
- **Mot de passe** : `password`

---

## 📁 Structure du projet
- `app/Models` : Modèles Eloquent (Candidat, Employé, Offre, etc.)
- `app/Http/Controllers` : Contrôleurs métiers (CandidateController, EmployeeController, etc.)
- `app/Http/Requests` : Validation des formulaires
- `app/Http/Middleware` : Middleware personnalisé (CheckRole)
- `app/Enums` : Énumérations (CandidateStatusEnum)
- `resources/views` : Vues Blade organisées par module
- `resources/js` : Scripts front-end (Alpine.js, Chart.js)
- `routes/web.php` : Définition des routes principales
- `database/seeders` : Seeders essentiels (Admin, LeaveTypes, EventTypes)
- `config/permission.php` : Configuration Spatie Permission

---

## 🔒 Sécurité & Permissions
- Authentification via Laravel Breeze
- Rôles et permissions dynamiques via Spatie Laravel Permission
- Middleware `auth`, `verified`, `role:admin` pour protéger les routes sensibles
- Validation stricte des formulaires avec Request classes

---

## 💼 Fonctionnalités métier

### Gestion des employés
- Calcul automatique des augmentations salariales :
  - Première augmentation (1000 DH) après 3 mois (si salaire initial = 3000 DH)
  - Augmentation de 3 ans appliquée à tous les employés
- Génération de PDF détaillé avec historique complet
- Suivi des documents et permis de conduire

### Tableau de bord
- Statistiques en temps réel (candidats, employés, absences, véhicules)
- Tendances mensuelles
- Vue d'ensemble des documents et événements
- Interface responsive et moderne

### Gestion des absences
- Types de congés configurables (Congé Payé, Maladie, Sans Solde, RTT)
- Approbation des demandes
- Génération de rapports PDF

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
- **PDF détaillé des employés** : Profil complet avec historique candidat
- **PDF des offres d'emploi** : Offres formatées professionnellement
- **Rapports d'absences** : Statistiques et détails
- **Rapports d'entretiens** : Évaluations et notes
- Export CSV des employés

---

## 🔧 Commandes artisan utiles
```bash
# Mise à jour du statut des employés (augmentations automatiques)
php artisan update:employee-status

# Génération de PDF détaillé pour un employé
php artisan employee:pdf-detail {employee_id}
```

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
