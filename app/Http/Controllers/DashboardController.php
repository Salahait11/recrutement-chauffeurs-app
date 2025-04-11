<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\DrivingTest;
use App\Models\LeaveRequest;
use App\Models\User; // Pour vérifier le rôle
use App\Models\Employee;
use App\Models\Offer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal.
     */
    public function index()
    {
        $user = Auth::user();
        $viewData = [];
        $viewData['userName'] = $user->name;

        // Si Admin / RH / Manager...
        if ($user->isAdmin()) {
            // Statistiques des candidats
            $viewData['candidateStats'] = [
                'nouveau' => Candidate::where('status', Candidate::STATUS_NOUVEAU)->count(),
                'en_cours' => Candidate::whereIn('status', [
                    Candidate::STATUS_CONTACTE,
                    Candidate::STATUS_ENTRETIEN,
                    Candidate::STATUS_TEST,
                    Candidate::STATUS_OFFRE
                ])->count(),
                'embauche' => Candidate::where('status', Candidate::STATUS_EMBAUCHE)->count(),
                'refuse' => Candidate::where('status', Candidate::STATUS_REFUSE)->count()
            ];

            // Statistiques des congés
            $viewData['leaveStats'] = [
                'en_attente' => LeaveRequest::where('status', 'pending')->count(),
                'aujourdhui' => LeaveRequest::whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->where('status', 'approved')
                    ->count(),
                'cette_semaine' => LeaveRequest::whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])
                    ->where('status', 'approved')
                    ->count()
            ];

            // Entretiens à venir (7 prochains jours)
            $viewData['upcomingInterviews'] = Interview::with('candidate')
                ->whereBetween('interview_date', [now(), now()->addDays(7)])
                ->orderBy('interview_date')
                ->limit(5)
                ->get();

            // Tests de conduite à venir (7 prochains jours)
            $viewData['upcomingDrivingTests'] = DrivingTest::with(['candidate', 'vehicle'])
                ->whereBetween('test_date', [now(), now()->addDays(7)])
                ->orderBy('test_date')
                ->limit(5)
                ->get();

            // Permis expirant bientôt (60 jours)
            $expirationThreshold = now()->addDays(60);
            $viewData['expiringLicensesCandidates'] = Candidate::whereNotIn('status', ['hired', 'rejected'])
                ->whereNotNull('driving_license_expiry')
                ->whereDate('driving_license_expiry', '>=', now())
                ->whereDate('driving_license_expiry', '<=', $expirationThreshold)
                ->orderBy('driving_license_expiry')
                ->limit(5)
                ->get();

            // Statistiques des employés
            $viewData['employeeStats'] = [
                'total' => Employee::where('status', 'active')->count(),
                'en_conge_aujourdhui' => Employee::whereHas('leaveRequests', function($query) {
                    $query->whereDate('start_date', '<=', now())
                        ->whereDate('end_date', '>=', now())
                        ->where('status', 'approved');
                })->count(),
                'nouveaux_ce_mois' => Employee::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count()
            ];

            // Statistiques des offres
            $viewData['offerStats'] = [
                'brouillon' => Offer::where('status', 'draft')->count(),
                'envoyee' => Offer::where('status', 'sent')->count(),
                'acceptee' => Offer::where('status', 'accepted')->count(),
                'refusee' => Offer::where('status', 'rejected')->count()
            ];
        }
        // Si Employé standard...
        elseif ($user->isEmployee()) {
            $employee = $user->employee;
            if ($employee) {
                // Mes demandes de congé en attente
                $viewData['myPendingLeaveRequests'] = LeaveRequest::where('employee_id', $employee->id)
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                // Mes dernières demandes traitées
                $viewData['myRecentLeaveRequests'] = LeaveRequest::where('employee_id', $employee->id)
                    ->whereIn('status', ['approved', 'rejected', 'canceled'])
                    ->orderBy('updated_at', 'desc')
                    ->limit(5)
                    ->get();

                // Mon solde de congés
                $viewData['myLeaveBalance'] = $employee->calculateLeaveBalance();
            }
        }

        return view('dashboard', $viewData);
    }
}