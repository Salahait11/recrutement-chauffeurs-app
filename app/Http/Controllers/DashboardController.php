<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Event;
use App\Models\Interview;
use App\Models\DrivingTest;
use App\Models\Offer;
use App\Models\Absence;
use App\Models\Vehicle;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $viewData = [];

        if ($user->hasRole('admin')) {
            $viewData = $this->getAdminStats();
        } elseif ($user->hasRole('manager')) {
            $viewData = $this->getManagerStats();
        } else {
            $viewData = $this->getEmployeeStats();
        }

        return view('dashboard', $viewData);
    }

    private function getAdminStats()
    {
        $upcomingSalaryIncreases = Employee::getUpcomingSalaryIncreases(30)->get();
        
        // Statistiques mensuelles
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        
        return [
            'userName' => Auth::user()->name,
            
            // Statistiques principales
            'candidateStats' => [
                'nouveau' => Candidate::where('status', Candidate::STATUS_NOUVEAU)->count(),
                'en_cours' => Candidate::where('status', Candidate::STATUS_ENTRETIEN)->count(),
                'embauche' => Candidate::where('status', Candidate::STATUS_EMBAUCHE)->count(),
                'refuse' => Candidate::where('status', Candidate::STATUS_REFUSE)->count(),
                'total' => Candidate::count(),
                'nouveaux_ce_mois' => Candidate::where('created_at', '>=', $currentMonth)->count(),
                'nouveaux_semaine' => Candidate::where('created_at', '>=', now()->startOfWeek())->count(),
            ],
            
            'leaveStats' => [
                'en_attente' => LeaveRequest::where('status', 'pending')->count(),
                'aujourdhui' => LeaveRequest::whereDate('start_date', today())->count(),
                'cette_semaine' => LeaveRequest::whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'ce_mois' => LeaveRequest::where('created_at', '>=', $currentMonth)->count(),
                'approuves_ce_mois' => LeaveRequest::where('status', 'approved')
                    ->where('created_at', '>=', $currentMonth)->count(),
            ],
            
            'employeeStats' => [
                'total' => Employee::where('status', 'active')->count(),
                'en_conge_aujourdhui' => Employee::whereHas('leaveRequests', function($query) {
                    $query->whereDate('start_date', '<=', today())
                          ->whereDate('end_date', '>=', today())
                          ->where('status', 'approved');
                })->count(),
                'nouveaux_ce_mois' => Employee::where('created_at', '>=', $currentMonth)->count(),
                'nouveaux_semaine' => Employee::where('created_at', '>=', now()->startOfWeek())->count(),
                'terminations_ce_mois' => Employee::where('status', 'terminated')
                    ->where('termination_date', '>=', $currentMonth)->count(),
                'en_conge_ce_mois' => Employee::whereHas('leaveRequests', function($query) use ($currentMonth) {
                    $query->where('status', 'approved')
                          ->where(function($q) use ($currentMonth) {
                              $q->where('start_date', '>=', $currentMonth)
                                ->orWhere('end_date', '>=', $currentMonth);
                          });
                })->count(),
            ],
            
            'offerStats' => [
                'brouillon' => Offer::where('status', Offer::STATUS_BROUILLON)->count(),
                'envoyee' => Offer::where('status', Offer::STATUS_ENVOYEE)->count(),
                'acceptee' => Offer::where('status', Offer::STATUS_ACCEPTEE)->count(),
                'refusee' => Offer::where('status', Offer::STATUS_REFUSEE)->count(),
                'total' => Offer::count(),
                'ce_mois' => Offer::where('created_at', '>=', $currentMonth)->count(),
            ],
            
            // Nouvelles statistiques
            'absenceStats' => [
                'aujourdhui' => Absence::whereDate('absence_date', today())->count(),
                'cette_semaine' => Absence::whereBetween('absence_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'ce_mois' => Absence::where('absence_date', '>=', $currentMonth)->count(),
                'total' => Absence::count(),
            ],
            
            'vehicleStats' => [
                'total' => Vehicle::count(),
                'disponibles' => Vehicle::where('is_available', true)->count(),
                'en_maintenance' => Vehicle::where('is_available', false)->count(),
                'utilises' => 0, // Pas de statut "utilisé" dans la structure actuelle
            ],
            
            'documentStats' => [
                'total' => Document::count(),
                'ce_mois' => Document::where('created_at', '>=', $currentMonth)->count(),
                'cette_semaine' => Document::where('created_at', '>=', now()->startOfWeek())->count(),
            ],
            
            'interviewStats' => [
                'planifies' => Interview::where('status', Interview::STATUS_PLANIFIE)->count(),
                'aujourdhui' => Interview::whereDate('interview_date', today())->count(),
                'cette_semaine' => Interview::whereBetween('interview_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'ce_mois' => Interview::where('interview_date', '>=', $currentMonth)->count(),
            ],
            
            'drivingTestStats' => [
                'planifies' => DrivingTest::where('status', DrivingTest::STATUS_PLANIFIE)->count(),
                'aujourdhui' => DrivingTest::whereDate('test_date', today())->count(),
                'cette_semaine' => DrivingTest::whereBetween('test_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'ce_mois' => DrivingTest::where('test_date', '>=', $currentMonth)->count(),
            ],
            
            // Évolutions mensuelles
            'monthlyTrends' => [
                'candidates' => [
                    'current' => Candidate::where('created_at', '>=', $currentMonth)->count(),
                    'previous' => Candidate::whereBetween('created_at', [$lastMonth, $currentMonth])->count(),
                ],
                'employees' => [
                    'current' => Employee::where('created_at', '>=', $currentMonth)->count(),
                    'previous' => Employee::whereBetween('created_at', [$lastMonth, $currentMonth])->count(),
                ],
                'leaves' => [
                    'current' => LeaveRequest::where('created_at', '>=', $currentMonth)->count(),
                    'previous' => LeaveRequest::whereBetween('created_at', [$lastMonth, $currentMonth])->count(),
                ],
            ],
            
            // Données pour les listes
            'upcomingInterviews' => Interview::with('candidate')
                ->where('status', Interview::STATUS_PLANIFIE)
                ->where('interview_date', '>=', now())
                ->orderBy('interview_date')
                ->take(5)
                ->get(),
                
            'upcomingDrivingTests' => DrivingTest::with(['candidate', 'vehicle'])
                ->where('status', DrivingTest::STATUS_PLANIFIE)
                ->where('test_date', '>=', now())
                ->orderBy('test_date')
                ->take(5)
                ->get(),
                
            'expiringLicensesCandidates' => Candidate::where('driving_license_expiry', '>=', now())
                ->where('driving_license_expiry', '<=', now()->addDays(30))
                ->orderBy('driving_license_expiry')
                ->take(5)
                ->get(),
                
            'upcomingSalaryIncreases' => $upcomingSalaryIncreases,
            
            'recentAbsences' => Absence::with('employee.user')
                ->orderBy('absence_date', 'desc')
                ->take(5)
                ->get(),
                
            'recentDocuments' => Document::with('candidate')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
    }

    private function getManagerStats()
    {
        $currentMonth = now()->startOfMonth();
        
        return [
            'userName' => Auth::user()->name,
            
            'candidateStats' => [
                'nouveau' => Candidate::where('status', Candidate::STATUS_NOUVEAU)->count(),
                'en_cours' => Candidate::where('status', Candidate::STATUS_ENTRETIEN)->count(),
                'embauche' => Candidate::where('status', Candidate::STATUS_EMBAUCHE)->count(),
                'refuse' => Candidate::where('status', Candidate::STATUS_REFUSE)->count(),
                'total' => Candidate::count(),
                'nouveaux_ce_mois' => Candidate::where('created_at', '>=', $currentMonth)->count(),
            ],
            
            'leaveStats' => [
                'en_attente' => LeaveRequest::where('status', 'pending')->count(),
                'aujourdhui' => LeaveRequest::whereDate('start_date', today())->count(),
                'cette_semaine' => LeaveRequest::whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'ce_mois' => LeaveRequest::where('created_at', '>=', $currentMonth)->count(),
            ],
            
            'interviewStats' => [
                'planifies' => Interview::where('status', Interview::STATUS_PLANIFIE)->count(),
                'aujourdhui' => Interview::whereDate('interview_date', today())->count(),
                'cette_semaine' => Interview::whereBetween('interview_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            
            'drivingTestStats' => [
                'planifies' => DrivingTest::where('status', DrivingTest::STATUS_PLANIFIE)->count(),
                'aujourdhui' => DrivingTest::whereDate('test_date', today())->count(),
                'cette_semaine' => DrivingTest::whereBetween('test_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            
            'upcomingInterviews' => Interview::with('candidate')
                ->where('status', Interview::STATUS_PLANIFIE)
                ->where('interview_date', '>=', now())
                ->orderBy('interview_date')
                ->take(5)
                ->get(),
                
            'upcomingDrivingTests' => DrivingTest::with(['candidate', 'vehicle'])
                ->where('status', DrivingTest::STATUS_PLANIFIE)
                ->where('test_date', '>=', now())
                ->orderBy('test_date')
                ->take(5)
                ->get(),
        ];
    }

    private function getEmployeeStats()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        return [
            'userName' => $user->name,
            
            'myLeaveStats' => [
                'en_attente' => LeaveRequest::where('employee_id', $employee->id ?? 0)
                    ->where('status', 'pending')->count(),
                'approuves_ce_mois' => LeaveRequest::where('employee_id', $employee->id ?? 0)
                    ->where('status', 'approved')
                    ->where('created_at', '>=', now()->startOfMonth())->count(),
                'total_ce_mois' => LeaveRequest::where('employee_id', $employee->id ?? 0)
                    ->where('created_at', '>=', now()->startOfMonth())->count(),
            ],
            
            'myAbsenceStats' => [
                'ce_mois' => Absence::where('employee_id', $employee->id ?? 0)
                    ->where('absence_date', '>=', now()->startOfMonth())->count(),
                'total' => Absence::where('employee_id', $employee->id ?? 0)->count(),
            ],
            
            'myPendingLeaveRequests' => LeaveRequest::where('employee_id', $employee->id ?? 0)
                ->where('status', 'pending')
                ->get(),
                
            'myRecentLeaveRequests' => LeaveRequest::where('employee_id', $employee->id ?? 0)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
                
            'myRecentAbsences' => Absence::where('employee_id', $employee->id ?? 0)
                ->orderBy('absence_date', 'desc')
                ->take(5)
                ->get(),
        ];
    }

    private function getRecentActivities()
    {
        // Logique pour récupérer les activités récentes
        return [];
    }
}