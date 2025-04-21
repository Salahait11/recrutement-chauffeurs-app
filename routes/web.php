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
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\EvaluationCriterionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']) 
      ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Candidats & Documents
    Route::resource('candidates', CandidateController::class);
    Route::post('/candidates/{candidate}/documents', [DocumentController::class, 'store'])->name('candidates.documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Entretiens & Evaluations liées
    Route::resource('interviews', InterviewController::class);

    // Tests Conduite & Evaluations liées
    Route::resource('driving-tests', DrivingTestController::class);
    Route::get('/driving-tests/{drivingTest}/evaluations/create', [EvaluationController::class, 'createForDrivingTest'])->name('driving-tests.evaluations.create');

    // Evaluations
    Route::resource('evaluations', EvaluationController::class);

    // Offres
    Route::resource('offers', OfferController::class);
    Route::get('/offers/create/candidate/{candidate}', [OfferController::class, 'createForCandidate'])
        ->name('offers.create-for-candidate');
    Route::post('/offers/{offer}/update-status', [OfferController::class, 'updateStatus'])
        ->name('offers.update-status');
    Route::get('/offers/{offer}/pdf', [OfferController::class, 'downloadOfferPdf'])->name('offers.pdf');

    Route::get('/employees/{employee}/pdf', [EmployeeController::class, 'downloadEmployeePdf'])->name('employees.pdf');
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
     Route::resource('vehicles', VehicleController::class);
      Route::resource('evaluation-criteria', EvaluationCriterionController::class)
          ->parameters(['evaluation-criteria' => 'criterion']); // Renomme paramètre
      Route::get('/reports/export/employees', [ReportController::class, 'exportEmployeesCsv'])
          ->name('reports.export.employees');
    });


}); // Fin groupe auth

require __DIR__.'/auth.php';
