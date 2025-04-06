<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\DrivingTest;
use App\Models\LeaveRequest;
use App\Models\User; // Pour vérifier le rôle

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal.
     */
    public function index()
    {
        $user = Auth::user();
        $viewData = [];

        // Données communes (optionnel)
        $viewData['userName'] = $user->name;

        // Données spécifiques selon le rôle
        if ($user->isAdmin() || $user->hasRole('recruiter') || $user->hasRole('manager') || $user->hasRole('rh_manager')) {
            // Pour Admin, Recruteur, Manager, RH...
            $viewData['newCandidatesCount'] = Candidate::where('status', 'new')->count();
            $viewData['pendingLeaveRequestsCount'] = LeaveRequest::where('status', 'pending')->count();
            $viewData['upcomingInterviews'] = Interview::with('candidate')
                                                    ->where('status', 'scheduled')
                                                    ->where('interview_date', '>=', now())
                                                    ->orderBy('interview_date', 'asc')
                                                    ->limit(5)
                                                    ->get();
            $viewData['upcomingDrivingTests'] = DrivingTest::with('candidate', 'vehicle')
                                                    ->where('status', 'scheduled')
                                                    ->where('test_date', '>=', now())
                                                    ->orderBy('test_date', 'asc')
                                                    ->limit(5)
                                                    ->get();

        } elseif ($user->isEmployee()) {
            // Pour un Employé standard
            $employee = $user->employee;
            if ($employee) {
                $viewData['myPendingLeaveRequests'] = LeaveRequest::with('leaveType')
                                                            ->where('employee_id', $employee->id)
                                                            ->where('status', 'pending')
                                                            ->orderBy('created_at', 'desc')
                                                            ->limit(5)
                                                            ->get();
                $viewData['myRecentLeaveRequests'] = LeaveRequest::with('leaveType')
                                                            ->where('employee_id', $employee->id)
                                                            ->whereIn('status', ['approved', 'rejected', 'canceled'])
                                                            ->orderBy('approved_at', 'desc') // Ou created_at ?
                                                            ->limit(5)
                                                            ->get();
                // On pourrait aussi afficher les prochains entretiens/tests auxquels il participe (si applicable)
            }
        }

        // Passe toutes les données collectées à la vue 'dashboard'
        return view('dashboard', $viewData);
    }
}