# Guide Technique du Système de Recrutement

## Table des Matières
1. [Architecture](#architecture)
2. [Base de Données](#base-de-données)
3. [Contrôleurs](#contrôleurs)
4. [Modèles](#modèles)
5. [Vues](#vues)
6. [API](#api)
7. [Sécurité](#sécurité)
8. [Déploiement](#déploiement)

## Architecture

### Structure du Projet
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
```

### Technologies Utilisées
- Laravel 10.x
- PHP 8.1+
- MySQL 5.7+
- Tailwind CSS
- JavaScript

## Base de Données

### Tables Principales
1. **users**
   - id
   - name
   - email
   - password
   - role
   - created_at
   - updated_at

2. **employees**
   - id
   - user_id
   - department
   - position
   - hire_date
   - status
   - created_at
   - updated_at

3. **absences**
   - id
   - employee_id
   - start_date
   - end_date
   - reason
   - status
   - created_at
   - updated_at

### Relations
- User -> Employee (One to One)
- Employee -> Absences (One to Many)
- User -> Documents (One to Many)

## Contrôleurs

### UserController
```php
class UserController extends Controller
{
    public function index()
    {
        // Liste des utilisateurs
    }

    public function create()
    {
        // Création d'un utilisateur
    }

    public function store()
    {
        // Enregistrement d'un utilisateur
    }

    public function edit()
    {
        // Modification d'un utilisateur
    }

    public function update()
    {
        // Mise à jour d'un utilisateur
    }

    public function destroy()
    {
        // Suppression d'un utilisateur
    }
}
```

### EmployeeController
```php
class EmployeeController extends Controller
{
    public function index()
    {
        // Liste des employés
    }

    public function create()
    {
        // Création d'un employé
    }

    public function store()
    {
        // Enregistrement d'un employé
    }

    public function show()
    {
        // Affichage d'un employé
    }

    public function edit()
    {
        // Modification d'un employé
    }

    public function update()
    {
        // Mise à jour d'un employé
    }

    public function destroy()
    {
        // Suppression d'un employé
    }
}
```

## Modèles

### User
```php
class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}
```

### Employee
```php
class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department',
        'position',
        'hire_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}
```

## Vues

### Structure des Templates
- Layout principal : `resources/views/layouts/app.blade.php`
- Composants réutilisables : `resources/views/components/`
- Pages spécifiques : `resources/views/admin/`

### Composants Principaux
- Navigation
- Formulaires
- Tableaux
- Modales
- Alertes

## API

### Routes API
```php
Route::prefix('api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/absences', [AbsenceController::class, 'index']);
});
```

### Format de Réponse
```json
{
    "success": true,
    "data": [],
    "message": "Opération réussie"
}
```

## Sécurité

### Middlewares
- Authentification
- Autorisation
- CSRF Protection
- Rate Limiting

### Validation
- Formulaires
- Données API
- Fichiers uploadés

## Déploiement

### Préparation
1. Mettre à jour les dépendances
2. Exécuter les migrations
3. Configurer l'environnement

### Étapes
1. Cloner le repository
2. Installer les dépendances
3. Configurer .env
4. Exécuter les migrations
5. Lancer le serveur

### Maintenance
- Sauvegardes régulières
- Mises à jour de sécurité
- Monitoring des performances 