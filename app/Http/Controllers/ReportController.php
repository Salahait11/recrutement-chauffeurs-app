<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Absence;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Affiche la page principale des rapports
     */
   public function index(Request $request)
    {
        // --- Dates du Filtre ---
        $defaultStartDate = Carbon::now()->subMonth()->startOfDay(); // Défaut: Début mois dernier
        $defaultEndDate = Carbon::now()->endOfDay();                  // Défaut: Fin aujourd'hui

        $validatedDates = $request->validate([
            'start_date' => 'sometimes|nullable|date|before_or_equal:end_date',
            'end_date' => 'sometimes|nullable|date|after_or_equal:start_date',
        ]);

        $startDate = isset($validatedDates['start_date']) ? Carbon::parse($validatedDates['start_date'])->startOfDay() : $defaultStartDate;
        $endDate = isset($validatedDates['end_date']) ? Carbon::parse($validatedDates['end_date'])->endOfDay() : $defaultEndDate;

        // --- Initialisation des variables ---
        $rawCandidateStats = collect(); $candidateChartLabels = []; $candidateChartData = [];
        $rawEmployeeStats = collect(); $employeeChartLabels = []; $employeeChartData = [];
        $upcomingEvents = collect(); // Renommé en $periodEvents pour la clarté

        try {
            // --- Statistiques Globales (non filtrées par date) ---
            $rawCandidateStats = Candidate::query()->select('status', DB::raw('count(*) as total'))->groupBy('status')->orderBy('status')->pluck('total', 'status');
            if ($rawCandidateStats->isNotEmpty()) {
                $candidateChartLabels = $rawCandidateStats->keys()->map(fn($status) => ucfirst($status))->toArray();
                $candidateChartData = $rawCandidateStats->values()->toArray();
            }

            $rawEmployeeStats = Employee::query()->select('status', DB::raw('count(*) as total'))->groupBy('status')->orderBy('status')->pluck('total', 'status');
            if ($rawEmployeeStats->isNotEmpty()) {
                $employeeChartLabels = $rawEmployeeStats->keys()->map(fn($status) => ucfirst($status))->toArray();
                $employeeChartData = $rawEmployeeStats->values()->toArray();
            }

            // --- Événements (Congés + Absences) DANS LA PÉRIODE sélectionnée ---
            $leavesInPeriod = LeaveRequest::with('employee.user', 'leaveType')
                ->where('status', 'approved')
                // Critère de chevauchement: Un congé est dans la période si
                // sa date de début est avant ou égale à la fin de la période ET
                // sa date de fin est après ou égale au début de la période.
                ->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate)
                ->orderBy('start_date', 'asc')
                ->get();

            $absencesInPeriod = Absence::with('employee.user')
                ->where('absence_date', '>=', $startDate->toDateString())
                ->where('absence_date', '<=', $endDate->toDateString())
                ->orderBy('absence_date', 'asc')
                ->get();

            // Formater et Fusionner les événements de la période
            $periodEvents = $leavesInPeriod->map(function($item) {
                    if (!$item->employee?->user || !$item->leaveType) return null;
                    return (object)[
                        'date' => $item->start_date, // Carbon object
                        'end_date' => $item->end_date, // Carbon object
                        'employee_name' => $item->employee->user->name,
                        'type' => 'Congé: '. $item->leaveType->name,
                        'css_class' => 'text-green-700 dark:text-green-300', // Style pour congé
                        'url' => route('leave-requests.show', $item->id), // Lien vers la demande
                        'is_absence' => false
                    ];
                })->filter()
                ->merge($absencesInPeriod->map(function($item) {
                     if (!$item->employee?->user) return null;
                     $startAbsence = Carbon::parse($item->absence_date->toDateString() . ' ' . ($item->start_time ?? '00:00:00'));
                     $endAbsence = $item->end_time ? Carbon::parse($item->absence_date->toDateString() . ' ' . $item->end_time) : $item->absence_date->copy()->endOfDay();
                     return (object)[
                         'date' => $startAbsence, // Carbon object
                         'end_date' => $endAbsence, // Carbon object
                         'employee_name' => $item->employee->user->name,
                         'type' => 'Absence: '.($item->reason_type ?? 'N/C'),
                         'css_class' => $item->is_justified ? 'text-yellow-700 dark:text-yellow-300' : 'text-red-700 dark:text-red-300', // Style pour absence
                         'url' => route('admin.absences.edit', $item->id), // Lien vers l'absence
                         'is_absence' => true
                     ];
                 })->filter())
                ->sortBy('date'); // Trier par date de début

        } catch (\Exception $e) {
            Log::error("Erreur Génération Rapport: " . $e->getMessage());
            session()->flash('error', 'Impossible de charger toutes les données du rapport.');
        }

        // --- Passer les données à la vue ---
         return view('admin.reports.index', compact(
             'rawCandidateStats', 'candidateChartLabels', 'candidateChartData',
             'rawEmployeeStats', 'employeeChartLabels', 'employeeChartData',
             'periodEvents', // Renommée pour la clarté
             'startDate',
             'endDate'
         ));
    }

    /**
     * Récupère les statistiques des candidats
     */
    protected function getCandidateStats(array $data): array
    {
        $rawStats = Candidate::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->pluck('total', 'status');

        if ($rawStats->isNotEmpty()) {
            $data['rawCandidateStats'] = $rawStats;
            $data['candidateChartLabels'] = $rawStats->keys()
                ->map(fn($status) => ucfirst($status))
                ->toArray();
            $data['candidateChartData'] = $rawStats->values()
                ->map(fn($val) => (int)$val)
                ->toArray();
        }

        return $data;
    }

    /**
     * Récupère les statistiques des employés
     */
    protected function getEmployeeStats(array $data): array
    {
        $rawStats = Employee::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->pluck('total', 'status');

        if ($rawStats->isNotEmpty()) {
            $data['rawEmployeeStats'] = $rawStats;
            $data['employeeChartLabels'] = $rawStats->keys()
                ->map(fn($status) => ucfirst($status))
                ->toArray();
            $data['employeeChartData'] = $rawStats->values()
                ->map(fn($val) => (int)$val)
                ->toArray();
        }

        return $data;
    }

    /**
     * Récupère les événements à venir (congés et absences)
     */
    protected function getUpcomingEvents(): Collection
    {
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(30);

        // Congés approuvés
        $leaves = LeaveRequest::with(['employee.user', 'leaveType'])
            ->where('status', 'approved')
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->get()
            ->map(function ($leave) {
                return (object)[
                    'date' => $leave->start_date,
                    'end_date' => $leave->end_date,
                    'employee_name' => optional($leave->employee)->user->name ?? 'Inconnu',
                    'type' => 'Congé: ' . optional($leave->leaveType)->name ?? 'N/A',
                    'css_class' => 'text-green-700 dark:text-green-300',
                    'is_absence' => false
                ];
            });

        // Absences à venir
        $absences = Absence::with(['employee.user'])
            ->whereBetween('absence_date', [$startDate, $endDate])
            ->get()
            ->map(function ($absence) {
                $start = Carbon::parse($absence->absence_date->toDateString() . ' ' . ($absence->start_time ?? '00:00:00'));
                $end = $absence->end_time 
                    ? Carbon::parse($absence->absence_date->toDateString() . ' ' . $absence->end_time)
                    : $absence->absence_date->copy()->endOfDay();

                return (object)[
                    'date' => $start,
                    'end_date' => $end,
                    'employee_name' => optional($absence->employee)->user->name ?? 'Inconnu',
                    'type' => 'Absence: ' . ($absence->reason_type ?? 'N/C'),
                    'css_class' => $absence->is_justified 
                        ? 'text-yellow-700 dark:text-yellow-300' 
                        : 'text-red-700 dark:text-red-300',
                    'is_absence' => true
                ];
            });

        return $leaves->merge($absences)
            ->sortBy('date')
            ->take(10);
    }

    /**
     * Export des employés au format CSV
     */
    public function exportEmployeesCsv(): StreamedResponse
    {
        $fileName = 'employes_actifs_' . date('Y-m-d') . '.csv';

        $employees = Employee::with('user')
            ->where('status', 'active')
            ->orderBy('hire_date')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'ID', 'Matricule', 'Nom', 'Email', 
            'Poste', 'Département', 'Date embauche', 'Salaire'
        ];

        $callback = function() use($employees, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');

            foreach ($employees as $employee) {
                $row = [
                    $employee->id,
                    $employee->employee_number,
                    optional($employee->user)->name,
                    optional($employee->user)->email,
                    $employee->job_title,
                    $employee->department,
                    $employee->hire_date->format('d/m/Y'),
                    $employee->salary . ' €'
                ];

                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}