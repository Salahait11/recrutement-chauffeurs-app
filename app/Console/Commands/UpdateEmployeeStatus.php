<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeaveRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateEmployeeStatus extends Command
{
    protected $signature = 'employees:update-status';
    protected $description = 'Met à jour le statut des employés en fonction de leurs congés';

    public function handle()
    {
        $this->info('Début de la mise à jour des statuts des employés...');

        try {
            DB::beginTransaction();

            // Trouver tous les employés en congé
            $employeesOnLeave = Employee::where('status', 'on_leave')
                ->whereHas('leaveRequests', function ($query) {
                    $query->where('status', 'approuve')
                        ->where('end_date', '<', Carbon::now());
                })
                ->get();

            $count = 0;
            foreach ($employeesOnLeave as $employee) {
                // Vérifier si l'employé a d'autres congés en cours
                $hasActiveLeaves = $employee->leaveRequests()
                    ->where('status', 'approuve')
                    ->where('end_date', '>=', Carbon::now())
                    ->exists();

                if (!$hasActiveLeaves) {
                    $employee->update(['status' => 'active']);
                    $count++;
                }
            }

            DB::commit();
            $this->info("Mise à jour terminée. {$count} employé(s) remis en statut actif.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la mise à jour des statuts des employés: " . $e->getMessage());
            $this->error("Une erreur est survenue lors de la mise à jour des statuts.");
        }
    }
} 