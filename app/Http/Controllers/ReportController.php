<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Absence;
use Illuminate\Support\Facades\DB; // Pour les comptages groupés
use Carbon\Carbon;

class ReportController extends Controller
{
     // Appliquer le middleware ici aussi (bonne pratique)
      

    /**
     * Affiche la page principale des rapports.
     */
    public function index()
    {
        // 1. Statistiques Candidats par Statut
        $candidateStats = Candidate::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status'); // Résultat: ['new' => 5, 'interview' => 2, ...]

        // 2. Statistiques Employés par Statut
        $employeeStats = Employee::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status'); // Résultat: ['active' => 10, 'terminated' => 1, ...]

        // 3. Prochains Congés/Absences (ex: dans les 30 prochains jours)
        $upcomingLeaves = LeaveRequest::with('employee.user', 'leaveType')
            ->where('status', 'approved')
            ->where('start_date', '>=', now())
            ->where('start_date', '<=', now()->addDays(30))
            ->orderBy('start_date', 'asc')
            ->limit(10)
            ->get();

        $upcomingAbsences = Absence::with('employee.user')
            ->where('absence_date', '>=', now()->toDateString()) // Seulement à partir d'aujourd'hui
            ->where('absence_date', '<=', now()->addDays(30))
            ->orderBy('absence_date', 'asc')
            ->limit(10)
            ->get();

        // Fusionner et trier les événements futurs
         $upcomingEvents = $upcomingLeaves->map(function($item) {
                return (object)[
                    'date' => $item->start_date,
                    'end_date' => $item->end_date, // Garder la vraie date de fin
                    'employee_name' => $item->employee?->user?->name ?? 'N/A',
                    'type' => 'Congé: '.($item->leaveType?->name ?? 'N/A'),
                    'css_class' => 'text-green-700 dark:text-green-300' // Exemple de classe
                ];
            })->merge($upcomingAbsences->map(function($item) {
                 return (object)[
                     'date' => Carbon::parse($item->absence_date->toDateString() . ' ' . ($item->start_time ?? '00:00:00')), // Combiner date et heure début si existe
                     'end_date' => $item->end_time ? Carbon::parse($item->absence_date->toDateString() . ' ' . $item->end_time) : Carbon::parse($item->absence_date->toDateString())->endOfDay(), // Fin de journée si pas d'heure de fin
                     'employee_name' => $item->employee?->user?->name ?? 'N/A',
                     'type' => 'Absence: '.($item->reason_type ?? 'N/C'),
                     'css_class' => $item->is_justified ? 'text-yellow-700 dark:text-yellow-300' : 'text-red-700 dark:text-red-300' // Exemple
                 ];
             }))->sortBy('date')->take(10); // Trier par date de début et prendre les 10 premiers


        // Passer les données à la vue
        return view('admin.reports.index', compact(
            'candidateStats',
            'employeeStats',
            'upcomingEvents'
            // Ajouter d'autres stats ici...
        ));
    }
}