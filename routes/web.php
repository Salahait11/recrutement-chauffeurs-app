<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\DrivingTestController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController; // Ajoute cette ligne
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\ReportController; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index']) // Appelle la méthode index
      ->middleware(['auth', 'verified'])
      ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Candidats & Documents
    Route::resource('candidates', CandidateController::class);
    Route::post('/candidates/{candidate}/documents', [DocumentController::class, 'store'])->name('candidates.documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Entretiens & Evaluations liées
    Route::resource('interviews', InterviewController::class);
    Route::get('/interviews/{interview}/evaluations/create', [EvaluationController::class, 'createForInterview'])->name('interviews.evaluations.create');

    // Tests Conduite & Evaluations liées
    Route::resource('driving-tests', DrivingTestController::class);
    Route::get('/driving-tests/{drivingTest}/evaluations/create', [EvaluationController::class, 'createForDrivingTest'])->name('driving-tests.evaluations.create');

    // Evaluations
    Route::resource('evaluations', EvaluationController::class);

    // Offres
    Route::get('/candidates/{candidate}/offers/create', [OfferController::class, 'createForCandidate'])->name('candidates.offers.create');
    Route::resource('offers', OfferController::class)->except(['create']);

    // Employés
    Route::resource('employees', EmployeeController::class);

    // Demandes de Congé
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::get('/calendar', [LeaveRequestController::class, 'calendar'])->name('calendar.index');
     Route::get('/leave-events', [LeaveRequestController::class, 'getLeaveEvents'])
          ->name('leave-requests.events');

    // --- Paramètres (Accès Restreint) ---

    // Gestion des Types de Congé
     Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () { // Préfixe URL + Nom

        // Gestion des Types de Congé
        Route::resource('leave-types', LeaveTypeController::class)
              ->parameters(['leave-types' => 'leaveType']); // Le nommage est géré par le groupe

        // Gestion des Utilisateurs
        Route::resource('users', UserController::class)
              ->except(['show']); // On garde create/store, on enlève show qui redirige vers edit

      Route::resource('absences', AbsenceController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index'); 
    });


}); // Fin groupe auth

require __DIR__.'/auth.php';
