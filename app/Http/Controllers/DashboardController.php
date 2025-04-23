<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Event;
use App\Models\Interview;
use App\Models\DrivingTest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        return [
            'totalCandidates' => Candidate::count(),
            'newCandidates' => Candidate::where('created_at', '>=', now()->subWeek())->count(),
            'totalEmployees' => Employee::count(),
            'pendingLeaveRequests' => LeaveRequest::where('status', 'pending')->count(),
            'upcomingEvents' => Event::where('start_date', '>=', now())->count(),
            'recentActivities' => $this->getRecentActivities(),
            'candidateStats' => [
                'nouveau' => Candidate::where('status', 'nouveau')->count(),
                'en_cours' => Candidate::where('status', 'en_cours')->count(),
                'embauche' => Candidate::where('status', 'embauche')->count(),
                'refuse' => Candidate::where('status', 'refuse')->count(),
            ],
            'leaveStats' => [
                'en_attente' => LeaveRequest::where('status', 'pending')->count(),
                'aujourdhui' => LeaveRequest::whereDate('start_date', today())->count(),
                'cette_semaine' => LeaveRequest::whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'employeeStats' => [
                'total' => Employee::count(),
                'en_conge_aujourdhui' => Employee::whereHas('leaveRequests', function($query) {
                    $query->whereDate('start_date', '<=', today())
                          ->whereDate('end_date', '>=', today())
                          ->where('status', 'approved');
                })->count(),
                'nouveaux_ce_mois' => Employee::where('created_at', '>=', now()->startOfMonth())->count(),
            ],
            'offerStats' => [
                'brouillon' => 0, // À implémenter
                'envoyee' => 0,   // À implémenter
                'acceptee' => 0,  // À implémenter
            ],
            'upcomingInterviews' => Interview::with('candidate')
                ->where('interview_date', '>=', now())
                ->orderBy('interview_date')
                ->take(5)
                ->get(),
            'upcomingDrivingTests' => DrivingTest::with(['candidate', 'vehicle'])
                ->where('test_date', '>=', now())
                ->orderBy('test_date')
                ->take(5)
                ->get(),
            'expiringLicensesCandidates' => Candidate::where('driving_license_expiry', '>=', now())
                ->where('driving_license_expiry', '<=', now()->addDays(30))
                ->orderBy('driving_license_expiry')
                ->take(5)
                ->get(),
        ];
    }

    private function getManagerStats()
    {
        return [
            'totalCandidates' => Candidate::count(),
            'newCandidates' => Candidate::where('created_at', '>=', now()->subWeek())->count(),
            'pendingLeaveRequests' => LeaveRequest::where('status', 'pending')->count(),
            'upcomingEvents' => Event::where('start_date', '>=', now())->count(),
            'recentActivities' => $this->getRecentActivities(),
            'candidateStats' => [
                'nouveau' => Candidate::where('status', 'nouveau')->count(),
                'en_cours' => Candidate::where('status', 'en_cours')->count(),
                'embauche' => Candidate::where('status', 'embauche')->count(),
                'refuse' => Candidate::where('status', 'refuse')->count(),
            ],
            'leaveStats' => [
                'en_attente' => LeaveRequest::where('status', 'pending')->count(),
                'aujourdhui' => LeaveRequest::whereDate('start_date', today())->count(),
                'cette_semaine' => LeaveRequest::whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'upcomingInterviews' => Interview::with('candidate')
                ->where('interview_date', '>=', now())
                ->orderBy('interview_date')
                ->take(5)
                ->get(),
            'upcomingDrivingTests' => DrivingTest::with(['candidate', 'vehicle'])
                ->where('test_date', '>=', now())
                ->orderBy('test_date')
                ->take(5)
                ->get(),
        ];
    }

    private function getEmployeeStats()
    {
        return [
            'pendingLeaveRequests' => LeaveRequest::where('employee_id', Auth::id())
                ->where('status', 'pending')
                ->count(),
            'upcomingEvents' => Event::where('start_date', '>=', now())->count(),
            'recentActivities' => $this->getRecentActivities(),
            'myPendingLeaveRequests' => LeaveRequest::where('employee_id', Auth::id())
                ->where('status', 'pending')
                ->get(),
            'myRecentLeaveRequests' => LeaveRequest::where('employee_id', Auth::id())
                ->orderBy('created_at', 'desc')
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