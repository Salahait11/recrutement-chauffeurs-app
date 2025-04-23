<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Absence;
use App\Enums\CandidateStatusEnum; // Assuming you have this for labels
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs; // For laravel-chartjs package

class ReportController extends Controller
{
    /**
     * Display the main reports page.
     * Fetches statistics and events based on optional date filters.
     */
    public function index(Request $request)
    {
        // --- 1. Date Filtering ---
        $defaultStartDate = Carbon::now()->subMonth()->startOfDay();
        $defaultEndDate = Carbon::now()->endOfDay();

        // Validate incoming dates
        $validatedDates = $request->validate([
            'start_date' => 'sometimes|nullable|date|before_or_equal:end_date',
            'end_date' => 'sometimes|nullable|date|after_or_equal:start_date',
        ]);

        $startDate = isset($validatedDates['start_date']) ? Carbon::parse($validatedDates['start_date'])->startOfDay() : $defaultStartDate;
        $endDate = isset($validatedDates['end_date']) ? Carbon::parse($validatedDates['end_date'])->endOfDay() : $defaultEndDate;

        Log::info('Report Generation Period:', ['start' => $startDate->toIso8601String(), 'end' => $endDate->toIso8601String()]);

        // --- 2. Initialize View Variables ---
        // Ensure all variables passed to the view have a default value
        $reportData = [
            'rawCandidateStats' => collect(),
            'candidateChart' => null,
            'rawEmployeeStats' => collect(),
            'employeeChart' => null,
            'leaveByTypeChart' => null,
            'periodEvents' => collect(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statusLabels' => CandidateStatusEnum::labels(), // Pass labels if needed in the view
        ];

        try {
            // --- 3. Fetch and Process Data ---

            // Candidate Statistics (Global - No date filter)
            $reportData = $this->generateCandidateStats($reportData);

            // Employee Statistics (Global - No date filter)
            $reportData = $this->generateEmployeeStats($reportData);

            // Period Events (Absences + Approved Leaves within the date range)
            // Fetch data once
            $approvedLeavesInPeriod = LeaveRequest::with(['employee.user', 'leaveType'])
                ->where('status', 'approuve') // Make sure to use the actual status value
                ->where(function($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate); // Overlapping periods
                })
                ->orderBy('start_date', 'asc')
                ->get();

            Log::info('Approved Leaves found in period:', ['count' => $approvedLeavesInPeriod->count()]);

            $absencesInPeriod = Absence::with(['employee.user', 'recorder']) // Eager load recorder if needed
                ->where('absence_date', '>=', $startDate->toDateString())
                ->where('absence_date', '<=', $endDate->toDateString())
                ->orderBy('absence_date', 'asc')
                ->get();

             Log::info('Absences found in period:', ['count' => $absencesInPeriod->count()]);


            // Process for Period Events List
            $reportData['periodEvents'] = $this->formatPeriodEvents($approvedLeavesInPeriod, $absencesInPeriod);

             // Process for Leave By Type Chart (using the already fetched $approvedLeavesInPeriod)
             $reportData = $this->generateLeaveByTypeChart($reportData, $approvedLeavesInPeriod);


        } catch (\Exception $e) {
            Log::error("Error Generating Report: " . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'Une erreur est survenue lors de la génération du rapport.');
            // Return view with default (empty) data initialized above
            return view('admin.reports.index', $reportData);
        }

        // --- 4. Return View with Data ---
        return view('admin.reports.index', $reportData);
    }

    // --- Helper Methods ---

    /**
     * Generate candidate statistics and chart object.
     */
    protected function generateCandidateStats(array $data): array
    {
        try {
            $data['rawCandidateStats'] = Candidate::query()
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->orderBy('status')
                ->pluck('total', 'status');

            if ($data['rawCandidateStats']->isNotEmpty()) {
                // Use labels from Enum or fallback to ucfirst
                $labels = $data['rawCandidateStats']->keys()->map(function($status) use ($data) {
                    return $data['statusLabels'][$status] ?? ucfirst($status);
                 })->toArray();

                $data['candidateChart'] = Chartjs::build()
                    ->name('candidateStatusChart')->type('pie')->size(['width' => 300, 'height' => 300])
                    ->labels($labels)
                    ->datasets([['label' => 'Candidats', 'data' => $data['rawCandidateStats']->values()->toArray()]])
                    ->options($this->getPieChartOptions()); // Use helper for options
            }
        } catch(\Exception $e) {
            Log::error('Failed to generate candidate stats/chart: '.$e->getMessage());
            $data['rawCandidateStats'] = collect();
            $data['candidateChart'] = null;
        }
        return $data;
    }

    /**
     * Generate employee statistics and chart object.
     */
    protected function generateEmployeeStats(array $data): array
    {
         try {
            $data['rawEmployeeStats'] = Employee::query()
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->orderBy('status')
                ->pluck('total', 'status');

            if ($data['rawEmployeeStats']->isNotEmpty()) {
                $data['employeeChart'] = Chartjs::build()
                    ->name('employeeStatusChart')->type('pie')->size(['width' => 300, 'height' => 300])
                    ->labels($data['rawEmployeeStats']->keys()->map(fn($status) => ucfirst($status))->toArray()) // Assuming simple statuses like 'active', 'terminated'
                    ->datasets([['label' => 'Employés', 'data' => $data['rawEmployeeStats']->values()->toArray()]])
                    ->options($this->getPieChartOptions()); // Use helper for options
            }
        } catch(\Exception $e) {
            Log::error('Failed to generate employee stats/chart: '.$e->getMessage());
            $data['rawEmployeeStats'] = collect();
            $data['employeeChart'] = null;
        }
        return $data;
    }

    /**
     * Generate Leave By Type chart object from pre-fetched leave data.
     */
    protected function generateLeaveByTypeChart(array $data, Collection $approvedLeavesInPeriod): array
    {
        try {
            if ($approvedLeavesInPeriod->isEmpty()) {
                Log::info('No approved leaves in period for chart.');
                $data['leaveByTypeChart'] = $this->getEmptyChart('leaveByTypeChart', 'Aucun congé approuvé pour cette période');
                return $data;
            }

            // Correctly group by Leave Type Name
            $leavesByType = $approvedLeavesInPeriod->groupBy(function ($leave) {
                return $leave->leaveType->name ?? 'Type Inconnu'; // Group by name
            })->map(function ($group) {
                return $group->sum('duration_days'); // Summing duration might be more insightful than count
                 // return $group->count(); // Or just count occurrences
            })->sortByDesc(fn($count) => $count); // Sort by count descending

            Log::info('Leave By Type chart data:', ['data' => $leavesByType->toArray()]);

             $data['leaveByTypeChart'] = Chartjs::build()
                ->name('leaveByTypeChart')
                ->type('bar') // Or 'doughnut' / 'pie'
                ->size(['width' => 400, 'height' => 300])
                ->labels($leavesByType->keys()->toArray())
                ->datasets([[
                    'label' => 'Jours de Congé Approuvés par Type',
                    'data' => $leavesByType->values()->toArray(),
                    // Add more background colors if needed
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.5)', 'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)', 'rgba(255, 206, 86, 0.5)',
                        'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)'
                        ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)', 'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
                        ],
                    'borderWidth' => 1
                ]])
                ->options($this->getBarChartOptions()); // Use helper for bar chart options

        } catch (\Exception $e) {
             Log::error('Failed to generate leave by type chart: '.$e->getMessage());
             $data['leaveByTypeChart'] = null;
        }
        return $data;
    }


    /**
     * Format and merge approved leaves and absences for the period event list.
     */
    protected function formatPeriodEvents(Collection $leaves, Collection $absences): Collection
    {
        $events = collect();

        // Format leaves
        foreach ($leaves as $leave) {
            // Basic check for essential related data
            if (!$leave->employee?->user || !$leave->leaveType) {
                Log::warning('Skipping leave in report event list due to missing relation', ['leave_id' => $leave->id]);
                continue;
            }
            $events->push([
                'id' => 'leave-'.$leave->id, // Unique ID prefix
                'date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'employee_name' => $leave->employee->user->name,
                'type' => 'Congé: '. $leave->leaveType->name,
                'css_class' => 'text-green-700 dark:text-green-300', // Example class
                'url' => route('leave-requests.show', $leave->id),
                'is_absence' => false
            ]);
        }

        // Format absences
        foreach ($absences as $absence) {
             // Basic check for essential related data
             if (!$absence->employee?->user) {
                 Log::warning('Skipping absence in report event list due to missing relation', ['absence_id' => $absence->id]);
                 continue;
             }
             // Absences are usually single days, represent start/end for consistency if needed
            $startAbsence = Carbon::parse($absence->absence_date->toDateString() . ' ' . ($absence->start_time ?? '00:00:00'));
            $endAbsence = $absence->end_time
                 ? Carbon::parse($absence->absence_date->toDateString() . ' ' . $absence->end_time)
                 : $absence->absence_date->copy()->endOfDay(); // Assume full day if no end time

            $events->push([
                'id' => 'absence-'.$absence->id, // Unique ID prefix
                'date' => $startAbsence,
                'end_date' => $endAbsence,
                'employee_name' => $absence->employee->user->name,
                'type' => 'Absence: '.($absence->reason_type ?? 'N/C'),
                'css_class' => $absence->is_justified ? 'text-yellow-700 dark:text-yellow-300' : 'text-red-700 dark:text-red-300', // Example classes
                'url' => route('admin.absences.edit', $absence->id), // Link to edit absence
                'is_absence' => true
            ]);
        }

        // Sort combined events by start date
        return $events->sortBy('date');
    }


    /**
     * Get default options for Pie/Doughnut charts.
     */
    protected function getPieChartOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => ['legend' => ['position' => 'bottom']]
        ];
    }

    /**
     * Get default options for Bar charts.
     */
     protected function getBarChartOptions(): array
     {
         return [
             'responsive' => true,
             'maintainAspectRatio' => false,
             'scales' => [
                 'y' => [
                     'beginAtZero' => true,
                     'ticks' => [
                         'stepSize' => 1, // Ensure whole numbers if counting items
                         'precision' => 0
                     ]
                 ]
             ],
             'plugins' => [
                 'legend' => [
                     'display' => false // Often better to hide legend for simple bar charts
                 ],
                 'title' => [
                     'display' => false, // Title can be set outside the chart
                     // 'text' => 'Default Bar Chart Title'
                 ]
             ]
         ];
     }

    /**
     * Generate an empty chart object for graceful fallback.
     */
    protected function getEmptyChart(string $name, string $titleText): ?object // Return type ChartjsResponse might be better if available
    {
        try {
            return Chartjs::build()
                ->name($name)
                ->type('bar') // Use bar as a generic fallback
                ->size(['width' => 400, 'height' => 200])
                ->labels(['N/A'])
                ->datasets([['label' => 'Données', 'data' => [0]]])
                ->options([
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'plugins' => [
                        'legend' => ['display' => false],
                        'title' => ['display' => true, 'text' => $titleText]
                    ],
                    'scales' => ['y' => ['beginAtZero' => true]]
                ]);
        } catch (\Exception $e) {
             Log::error('Failed to build empty chart object: '.$e->getMessage());
             return null;
        }
    }

    /**
     * Export active employees as CSV.
     */
    public function exportEmployeesCsv(): StreamedResponse
    {
        $fileName = 'employes_actifs_' . date('Y-m-d_His') . '.csv'; // Added time for uniqueness

        // Define columns clearly
        $columns = [
            'ID Employe', // employee.id
            'Matricule',  // employee.employee_number
            'Nom Complet',// user.name
            'Email',      // user.email
            'Poste',      // employee.job_title
            'Departement',// employee.department
            'Date Embauche', // employee.hire_date
            'Statut',      // employee.status
            'Manager',     // manager.user.name (Requires eager loading)
            // Add other relevant columns: 'N° Sécu', 'Banque' IF needed and authorized
        ];

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8", // Specify charset
            "Content-Disposition" => "attachment; filename=\"$fileName\"", // Ensure filename is quoted
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($columns) {
             // Use output buffer
            $file = fopen('php://output', 'w');
             // Add BOM for Excel UTF-8 compatibility if needed
             // fwrite($file, "\xEF\xBB\xBF"); 
            
            // Write header row
            fputcsv($file, $columns, ';'); // Use semicolon as separator

            // Fetch data in chunks to avoid memory issues with large datasets
            Employee::with(['user', 'manager.user']) // Eager load necessary relations
                ->where('status', 'active')
                ->orderBy('id') // Order for consistency
                ->chunk(200, function($employees) use ($file) {
                    foreach ($employees as $employee) {
                        $row = [
                            $employee->id,
                            $employee->employee_number ?? '',
                            $employee->user->name ?? '',
                            $employee->user->email ?? '',
                            $employee->job_title ?? '',
                            $employee->department ?? '',
                            optional($employee->hire_date)->format('d/m/Y') ?? '',
                            ucfirst($employee->status ?? ''),
                            $employee->manager->user->name ?? '', // Manager Name
                        ];
                         // Safely add sensitive data if authorized
                         // if (auth()->user()->can('exportSensitiveData')) {
                         //    $row[] = $employee->social_security_number ?? '';
                         // }
                         fputcsv($file, $row, ';');
                    }
                 });

            fclose($file);
        };

        // Return the streamed response
        return response()->stream($callback, 200, $headers);
    }
}