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
        if ($user->isAdmin() /* || ... autres rôles ... */) {
            // ... (stats candidats, congés, entretiens, tests - comme avant) ...
            $viewData['newCandidatesCount'] = Candidate::where('status', 'new')->count();
            $viewData['pendingLeaveRequestsCount'] = LeaveRequest::where('status', 'pending')->count();
            $viewData['upcomingInterviews'] = Interview::with('candidate')/*...*/->limit(5)->get();
            $viewData['upcomingDrivingTests'] = DrivingTest::with('candidate', 'vehicle')/*...*/->limit(5)->get();

            // --- AJOUTER LA REQUETE POUR LES PERMIS EXPIRANT ---
            $expirationThreshold = Carbon::now()->addDays(60); // Expiration dans les 60 prochains jours

            // 1. Employés actifs dont le permis expire bientôt (requiert colonne permis sur Employee ou User ?)
            //    NOTE: Actuellement, l'info permis est sur le Candidat. Il faudrait la copier sur l'Employé lors de la conversion.
            //    Supposons qu'on l'ait copiée sur User pour l'exemple (à adapter).
            /*
            $viewData['expiringLicensesEmployees'] = User::whereHas('employee', fn($q) => $q->where('status', 'active'))
                                                        ->whereNotNull('driving_license_expiry') // Si stocké sur User
                                                        ->whereDate('driving_license_expiry', '>=', now())
                                                        ->whereDate('driving_license_expiry', '<=', $expirationThreshold)
                                                        ->orderBy('driving_license_expiry', 'asc')
                                                        ->limit(5)
                                                        ->get(['id', 'name', 'driving_license_expiry']);
            */

            // 2. Ou plus simplement pour l'instant : Candidats (non embauchés/rejetés) dont le permis expire
            $viewData['expiringLicensesCandidates'] = Candidate::whereNotIn('status', ['hired', 'rejected']) // Statuts pertinents
                                                            ->whereNotNull('driving_license_expiry')
                                                            ->whereDate('driving_license_expiry', '>=', now())
                                                            ->whereDate('driving_license_expiry', '<=', $expirationThreshold)
                                                            ->orderBy('driving_license_expiry', 'asc')
                                                            ->limit(5)
                                                            ->get(['id', 'first_name', 'last_name', 'driving_license_expiry']);
            // --- FIN REQUETE PERMIS ---


        }
         // Si Employé standard...
         elseif ($user->isEmployee()) {
             // ... (données employé comme avant) ...
             $employee = $user->employee;
              if ($employee) { /* ... récupération demandes congé ... */ }
         }

        return view('dashboard', $viewData);
    }
}