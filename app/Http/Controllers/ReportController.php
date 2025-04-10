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
use IcehouseVentures\LaravelChartjs\Facades\Chartjs; // Pour le package laravel-chartjs

class ReportController extends Controller
{
     

    /**
     * Affiche la page principale des rapports avec filtres par date pour les événements.
     */
    public function index(Request $request)
    {
        // --- Dates du Filtre ---
        $defaultStartDate = Carbon::now()->subMonth()->startOfDay();
        $defaultEndDate = Carbon::now()->endOfDay();

        $validatedDates = $request->validate([
            'start_date' => 'sometimes|nullable|date|before_or_equal:end_date',
            'end_date' => 'sometimes|nullable|date|after_or_equal:start_date',
        ]);

        $startDate = isset($validatedDates['start_date']) ? Carbon::parse($validatedDates['start_date'])->startOfDay() : $defaultStartDate;
        $endDate = isset($validatedDates['end_date']) ? Carbon::parse($validatedDates['end_date'])->endOfDay() : $defaultEndDate;

        // --- Initialisation des variables ---
        // Initialise TOUTES les variables qui seront passées à la vue
        $rawCandidateStats = collect(); $candidateChart = null;
        $rawEmployeeStats = collect(); $employeeChart = null;
        $leaveByTypeChart = null;
        $periodEvents = collect(); // Renommée depuis upcomingEvents

        try {
            // --- Statistiques Candidats (Globales) ---
            $rawCandidateStats = Candidate::query()->select('status', DB::raw('count(*) as total'))->groupBy('status')->orderBy('status')->pluck('total', 'status');
            if ($rawCandidateStats->isNotEmpty()) {
                $candidateChart = Chartjs::build()
                    ->name('candidateStatusChart')->type('pie')->size(['width' => 300, 'height' => 300])
                    ->labels($rawCandidateStats->keys()->map(fn($status) => ucfirst($status))->toArray())
                    ->datasets([['label' => 'Candidats', 'data' => $rawCandidateStats->values()->toArray()]])
                    ->options(['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom']]]);
            }

            // --- Statistiques Employés (Globales) ---
             $rawEmployeeStats = Employee::query()->select('status', DB::raw('count(*) as total'))->groupBy('status')->orderBy('status')->pluck('total', 'status');
            if ($rawEmployeeStats->isNotEmpty()) {
                 $employeeChart = Chartjs::build()
                    ->name('employeeStatusChart')->type('pie')->size(['width' => 300, 'height' => 300])
                    ->labels($rawEmployeeStats->keys()->map(fn($status) => ucfirst($status))->toArray())
                    ->datasets([['label' => 'Employés', 'data' => $rawEmployeeStats->values()->toArray()]])
                    ->options(['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'bottom']]]);
            }

            // --- Stats Congés par Type sur la Période & Création Graphique ---
            $leaveByTypeStats = LeaveRequest::query()
                ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
                ->where('leave_requests.status', 'approved')
                ->where(function($q) use ($startDate, $endDate) { $q->where('leave_requests.start_date', '<=', $endDate)->where('leave_requests.end_date', '>=', $startDate); })
                ->select('leave_types.name as leave_type_name', DB::raw('count(leave_requests.id) as total'))
                ->groupBy('leave_types.name')->orderBy('leave_type_name')->pluck('total', 'leave_type_name');

            if ($leaveByTypeStats->isNotEmpty()) {
                $leaveByTypeChart = Chartjs::build()
                    ->name('leaveByTypeChart')->type('bar')->size(['width' => 400, 'height' => 200])
                    ->labels($leaveByTypeStats->keys()->toArray())
                    ->datasets([['label' => 'Nb Demandes Approuvées','data' => $leaveByTypeStats->values()->toArray()]])
                    ->options(['responsive' => true,'maintainAspectRatio' => false,'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1, 'precision' => 0]]],'plugins' => ['legend' => ['display' => false]]]);
            }

            // --- Événements (Congés + Absences) DANS LA PÉRIODE ---
             $leavesInPeriod = LeaveRequest::with('employee.user', 'leaveType')->where('status', 'approved')->where(function($q) use ($startDate, $endDate) { /*...*/ })->orderBy('start_date', 'asc')->get();
             $absencesInPeriod = Absence::with('employee.user')->where('absence_date', '>=', $startDate->toDateString())->where('absence_date', '<=', $endDate->toDateString())->orderBy('absence_date', 'asc')->get();

             // Formater et Fusionner
             $periodEvents = $leavesInPeriod->map(function($item) { if (!$item->employee?->user || !$item->leaveType) return null; return (object)['date' => $item->start_date,'end_date' => $item->end_date,'employee_name' => $item->employee->user->name,'type' => 'Congé: '. $item->leaveType->name,'css_class' => 'text-green-700 dark:text-green-300','url' => route('leave-requests.show', $item->id),'is_absence' => false]; })->filter()
                ->merge($absencesInPeriod->map(function($item) { if (!$item->employee?->user) return null; $startAbsence = Carbon::parse(...); $endAbsence = Carbon::parse(...); return (object)['date' => $startAbsence,'end_date' => $endAbsence,'employee_name' => $item->employee->user->name,'type' => 'Absence: '.($item->reason_type ?? 'N/C'),'css_class' => $item->is_justified ? 'text-yellow-700 dark:text-yellow-300' : 'text-red-700 dark:text-red-300','url' => route('admin.absences.edit', $item->id),'is_absence' => true]; })->filter())
                ->sortBy('date');

        } catch (\Exception $e) {
            Log::error("Erreur Génération Rapport: " . $e->getMessage());
            session()->flash('error', 'Impossible de charger toutes les données du rapport.');
            // Les variables $raw...Stats, $...Chart, $periodEvents resteront les collections/null initialisés.
        }

        // --- Passer TOUTES les données à la vue (y compris les stats brutes) ---
         return view('admin.reports.index', compact(
             'rawCandidateStats',    // <<< Données brutes pour la liste
             'candidateChart',       // <<< Objet Chartjs pour le graphique

             'rawEmployeeStats',     // <<< Données brutes pour la liste
             'employeeChart',        // <<< Objet Chartjs pour le graphique

             'leaveByTypeChart',     // <<< Objet Chartjs pour le graphique

             'periodEvents',         // <<< Liste des événements filtrés
             'startDate',            // <<< Date de début utilisée
             'endDate'               // <<< Date de fin utilisée
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