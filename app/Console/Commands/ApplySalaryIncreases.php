<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplySalaryIncreases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:apply-salary-increases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Applique automatiquement les augmentations de salaire prévues (3 mois et 3 ans) pour les employés.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début de l\'application des augmentations de salaire...');

        try {
            DB::beginTransaction();

            $now = Carbon::now();
            $firstIncreaseCount = 0;
            $secondIncreaseCount = 0;

            // 1. Appliquer les augmentations à 3 mois
            $employeesForFirstIncrease = Employee::where('has_first_increase', false)
                ->whereNotNull('three_months_increase_date')
                ->where('three_months_increase_date', '<=', $now)
                ->get();

            foreach ($employeesForFirstIncrease as $employee) {
                $employee->salary = 4000; // Augmentation de 3000 à 4000
                $employee->has_first_increase = true;
                $employee->save();
                $firstIncreaseCount++;

                $this->info("✅ {$employee->candidate->first_name} {$employee->candidate->last_name} : Première augmentation appliquée (3000 → 4000 DH)");
            }

            // 2. Appliquer les augmentations à 3 ans
            $employeesForSecondIncrease = Employee::where('has_first_increase', true)
                ->where('has_second_increase', false)
                ->whereNotNull('three_years_increase_date')
                ->where('three_years_increase_date', '<=', $now)
                ->get();

            foreach ($employeesForSecondIncrease as $employee) {
                $oldSalary = $employee->salary;
                $employee->salary += 500; // Augmentation de 500 DH
                $employee->has_second_increase = true;
                $employee->save();
                $secondIncreaseCount++;

                $this->info("✅ {$employee->candidate->first_name} {$employee->candidate->last_name} : Deuxième augmentation appliquée ({$oldSalary} → {$employee->salary} DH)");
            }

            DB::commit();

            $this->info("Application terminée !");
            $this->info("📊 {$firstIncreaseCount} augmentation(s) de 3 mois appliquée(s).");
            $this->info("📊 {$secondIncreaseCount} augmentation(s) de 3 ans appliquée(s).");

            if ($firstIncreaseCount === 0 && $secondIncreaseCount === 0) {
                $this->warn("Aucune augmentation à appliquer pour le moment.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de l'application des augmentations de salaire: " . $e->getMessage());
            $this->error("Une erreur est survenue lors de l'application des augmentations.");
        }
    }
}
