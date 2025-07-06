<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Employee extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'candidate_id',
        'employee_number',
        'hire_date',
        'job_title',
        'department',
        'manager_id',
        'work_location',
        'social_security_number',
        'bank_details',
        'salary',
        'initial_salary',
        'termination_date',
        'three_months_increase_date',
        'has_first_increase',
        'three_years_increase_date',
        'has_second_increase',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'termination_date' => 'date',
        'three_months_increase_date' => 'date',
        'three_years_increase_date' => 'date',
        'salary' => 'decimal:2',
        'initial_salary' => 'decimal:2',
        'has_first_increase' => 'boolean',
        'has_second_increase' => 'boolean',
    ];

    // Relation vers l'enregistrement User associé
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    // Relation vers l'enregistrement Candidate d'origine
    public function candidate(): BelongsTo { return $this->belongsTo(Candidate::class); }

    // Relation vers le manager (qui est aussi un User)
    public function manager(): BelongsTo { return $this->belongsTo(User::class, 'manager_id'); }

   public function leaveRequests(): HasMany { return $this->hasMany(LeaveRequest::class); }
   public function absences(): HasMany { return $this->hasMany(Absence::class); }
   
   /**
    * Retourne les employés avec une augmentation prévue dans moins d'un mois
    */
   public static function getUpcomingSalaryIncreases($days = 30)
   {
       $now = now();
       $limit = $now->copy()->addDays($days);
       
       return self::where(function($query) use ($now, $limit) {
           // Augmentation à 3 mois (première augmentation) - seulement si salaire initial = 3000 DH
           $query->where('has_first_increase', false)
                 ->where('initial_salary', 3000)
                 ->whereNotNull('three_months_increase_date')
                 ->whereBetween('three_months_increase_date', [$now, $limit]);
       })->orWhere(function($query) use ($now, $limit) {
           // Augmentation à 3 ans (deuxième augmentation) - pour tous les employés
           $query->where('has_second_increase', false)
                 ->whereNotNull('three_years_increase_date')
                 ->whereBetween('three_years_increase_date', [$now, $limit]);
       })->with('candidate');
   }

   /**
    * Détermine le type d'augmentation à venir
    */
   public function getUpcomingIncreaseType(): string
   {
       $now = now();
       
       // Vérifier l'augmentation à 3 mois (seulement si salaire initial = 3000 DH)
       if (!$this->has_first_increase && 
           $this->initial_salary == 3000 &&
           $this->three_months_increase_date && 
           $this->three_months_increase_date->isAfter($now) && 
           $this->three_months_increase_date->diffInDays($now) <= 30) {
           return 'Première augmentation (3 mois)';
       }
       
       // Vérifier l'augmentation à 3 ans (pour tous les employés)
       if (!$this->has_second_increase && 
           $this->three_years_increase_date && 
           $this->three_years_increase_date->isAfter($now) && 
           $this->three_years_increase_date->diffInDays($now) <= 30) {
           return 'Deuxième augmentation (3 ans)';
       }
       
       return '';
   }

   /**
    * Retourne la date de la prochaine augmentation
    */
   public function getUpcomingIncreaseDate(): ?string
   {
       $now = now();
       
       // Vérifier l'augmentation à 3 mois (seulement si salaire initial = 3000 DH)
       if (!$this->has_first_increase && 
           $this->initial_salary == 3000 &&
           $this->three_months_increase_date && 
           $this->three_months_increase_date->isAfter($now) && 
           $this->three_months_increase_date->diffInDays($now) <= 30) {
           return $this->three_months_increase_date->format('d/m/Y');
       }
       
       // Vérifier l'augmentation à 3 ans (pour tous les employés)
       if (!$this->has_second_increase && 
           $this->three_years_increase_date && 
           $this->three_years_increase_date->isAfter($now) && 
           $this->three_years_increase_date->diffInDays($now) <= 30) {
           return $this->three_years_increase_date->format('d/m/Y');
       }
       
       return null;
   }

   /**
    * Calcule et définit les dates d'augmentation automatique
    */
   public function calculateSalaryIncrease(): void
   {
       if (!$this->hire_date) {
           return;
       }

       // Calculer la date d'augmentation à 3 mois
       $this->three_months_increase_date = $this->hire_date->copy()->addMonths(3);
       
       // Calculer la date d'augmentation à 3 ans
       $this->three_years_increase_date = $this->hire_date->copy()->addYears(3);
       
       // Initialiser les statuts
       $this->has_first_increase = false;
       $this->has_second_increase = false;
       
       $this->save();
   }

   /**
    * Applique l'augmentation de salaire automatiquement
    */
   public function applySalaryIncrease(): bool
   {
       $now = now();
       
       // Appliquer l'augmentation à 3 mois (seulement si salaire initial = 3000 DH)
       if (!$this->has_first_increase && 
           $this->three_months_increase_date && 
           $this->three_months_increase_date->isBefore($now) &&
           $this->initial_salary == 3000) {
           $this->salary = 4000; // Augmentation de 3000 à 4000
           $this->has_first_increase = true;
           $this->save();
           return true;
       }
       
       // Appliquer l'augmentation à 3 ans (pour tous les employés, indépendamment de la première augmentation)
       if (!$this->has_second_increase && 
           $this->three_years_increase_date && 
           $this->three_years_increase_date->isBefore($now)) {
           $this->salary += 500; // Augmentation de 500 DH
           $this->has_second_increase = true;
           $this->save();
           return true;
       }
       
       return false;
   }
   
   public function getFormattedSalaryAttribute(): string
   {
       if (!$this->salary) return '-';
       return number_format($this->salary, 2, ',', ' ') . ' DH';
   }

   /**
    * Retourne le statut de la première augmentation (3 mois)
    */
   public function getFirstIncreaseStatus(): string
   {
       if ($this->has_first_increase) {
           return '✅ Appliquée';
       }
       
       // Si le salaire initial n'est pas 3000 DH, pas d'augmentation automatique
       if ($this->initial_salary != 3000) {
           return '❌ Non applicable (salaire initial ≠ 3000 DH)';
       }
       
       if ($this->three_months_increase_date) {
           $now = now();
           if ($this->three_months_increase_date->isBefore($now)) {
               return '⚠️ En attente d\'application';
           } else {
               $daysLeft = $this->three_months_increase_date->diffInDays($now, false);
               if ($daysLeft <= 30 && $daysLeft >= 0) {
                   return "🕐 Dans {$daysLeft} jour(s)";
               } else {
                   return "📅 Le " . $this->three_months_increase_date->format('d/m/Y');
               }
           }
       }
       
       return '❌ Non configurée';
   }

   /**
    * Retourne le statut de la deuxième augmentation (3 ans)
    */
   public function getSecondIncreaseStatus(): string
   {
       if ($this->has_second_increase) {
           return '✅ Appliquée';
       }
       
       if ($this->three_years_increase_date) {
           $now = now();
           if ($this->three_years_increase_date->isBefore($now)) {
               return '⚠️ En attente d\'application';
           } else {
               $daysLeft = $this->three_years_increase_date->diffInDays($now, false);
               if ($daysLeft <= 30 && $daysLeft >= 0) {
                   return "🕐 Dans {$daysLeft} jour(s)";
               } else {
                   return "📅 Le " . $this->three_years_increase_date->format('d/m/Y');
               }
           }
       }
       
       return '❌ Non configurée';
   }

   /**
    * Retourne la classe CSS pour le statut de la première augmentation
    */
   public function getFirstIncreaseStatusClass(): string
   {
       if ($this->has_first_increase) {
           return 'text-green-600 dark:text-green-400';
       }
       
       if ($this->three_months_increase_date && $this->three_months_increase_date->isBefore(now())) {
           return 'text-orange-600 dark:text-orange-400';
       }
       
       if ($this->three_months_increase_date && $this->three_months_increase_date->diffInDays(now()) <= 30) {
           return 'text-yellow-600 dark:text-yellow-400';
       }
       
       return 'text-gray-600 dark:text-gray-400';
   }

   /**
    * Retourne la classe CSS pour le statut de la deuxième augmentation
    */
   public function getSecondIncreaseStatusClass(): string
   {
       if ($this->has_second_increase) {
           return 'text-green-600 dark:text-green-400';
       }
       
       if (!$this->has_first_increase) {
           return 'text-gray-500 dark:text-gray-500';
       }
       
       if ($this->three_years_increase_date && $this->three_years_increase_date->isBefore(now())) {
           return 'text-orange-600 dark:text-orange-400';
       }
       
       if ($this->three_years_increase_date && $this->three_years_increase_date->diffInDays(now()) <= 30) {
           return 'text-yellow-600 dark:text-yellow-400';
       }
       
       return 'text-gray-600 dark:text-gray-400';
   }
}