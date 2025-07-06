<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class ApplyEmployeeSalaryIncreases extends Command
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
        $count3mois = 0;
        $count3ans = 0;
        $now = now();

        // 1. Appliquer l'augmentation de 3 mois
        $threeMonthsEmployees = Employee::where('salary_increase_status', 'pending')
            ->whereNotNull('salary_increase_date')
            ->where('salary_increase_date', '<=', $now)
            ->get();
        foreach ($threeMonthsEmployees as $employee) {
            if ($employee->applySalaryIncrease()) {
                $count3mois++;
                Log::info("Augmentation 3 mois appliquée à l'employé #{$employee->id}");
            }
        }

        // 2. Appliquer l'augmentation de 3 ans
        $threeYearsEmployees = Employee::where('three_years_increase_status', 'pending')
            ->whereNotNull('three_years_increase_date')
            ->where('three_years_increase_date', '<=', $now)
            ->get();
        foreach ($threeYearsEmployees as $employee) {
            if ($employee->applyThreeYearsIncrease()) {
                $count3ans++;
                Log::info("Augmentation 3 ans appliquée à l'employé #{$employee->id}");
            }
        }

        $this->info("$count3mois augmentation(s) de 3 mois appliquée(s).");
        $this->info("$count3ans augmentation(s) de 3 ans appliquée(s).");
        return 0;
    }
}
