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
use App\Http\Controllers\CompanySettingController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route PDF test
Route::get('/interviews/pdf', [InterviewController::class, 'generatePdf'])->name('interviews.pdf');
Route::get('/employees/pdf', [EmployeeController::class, 'generatePdf'])->name('employees.pdf');
Route::get('/employees/{employee}/pdf', [EmployeeController::class, 'downloadEmployeePdf'])->name('employees.single.pdf');
Route::get('/admin/absences/pdf', [AbsenceController::class, 'generatePdf'])->name('admin.absences.pdf');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']) 
      ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Candidats & Documents
    Route::get('/candidates/pdf', [CandidateController::class, 'generatePdf'])->name('candidates.pdf');
    Route::resource('candidates', CandidateController::class);
    Route::post('/candidates/{candidate}/documents', [DocumentController::class, 'store'])->name('candidates.documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Entretiens & Evaluations liées
    Route::resource('interviews', InterviewController::class);
    Route::patch('/interviews/{interview}/update-status', [InterviewController::class, 'updateStatus'])
        ->name('interviews.update-status');
    Route::post('/interviews/{interview}/cancel', [InterviewController::class, 'cancel'])->name('interviews.cancel');
    Route::post('/interviews/{interview}/start', [InterviewController::class, 'start'])->name('interviews.start');
    Route::post('/interviews/{interview}/complete', [InterviewController::class, 'complete'])->name('interviews.complete');

    // Tests Conduite & Evaluations liées
    Route::resource('driving-tests', DrivingTestController::class);

    // Evaluations
    Route::resource('evaluations', EvaluationController::class);

    // Offres
    Route::resource('offers', OfferController::class);
    Route::get('offers/create-for-candidate/{candidate}', [OfferController::class, 'createForCandidate'])->name('offers.createForCandidate');
    Route::post('/offers/{offer}/update-status', [OfferController::class, 'updateStatus'])
        ->name('offers.update-status');
    Route::get('/offers/{offer}/pdf', [OfferController::class, 'downloadOfferPdf'])->name('offers.pdf');

    Route::resource('employees', EmployeeController::class);

    // Demandes de Congé
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::get('/calendar', [LeaveRequestController::class, 'calendar'])->name('calendar.index');
     Route::get('/leave-events', [LeaveRequestController::class, 'getLeaveEvents'])
          ->name('leave-requests.events');

    // Routes pour les évaluations
    Route::get('/interviews/{interview}/evaluations/create', [EvaluationController::class, 'createForInterview'])->name('interviews.evaluations.create');
    Route::post('/interviews/{interview}/evaluations', [EvaluationController::class, 'store'])->name('interviews.evaluations.store');
    Route::get('/driving-tests/{drivingTest}/evaluations/create', [EvaluationController::class, 'createForDrivingTest'])->name('driving-tests.evaluations.create');
    Route::post('/driving-tests/{drivingTest}/evaluations', [EvaluationController::class, 'storeForDrivingTest'])->name('driving-tests.evaluations.store');
    Route::get('/evaluations/{evaluation}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::get('/evaluations/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('evaluations.edit');
    Route::put('/evaluations/{evaluation}', [EvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('/evaluations/{evaluation}', [EvaluationController::class, 'destroy'])->name('evaluations.destroy');

    // --- Paramètres (Accès Restreint) ---

    // Gestion des Types de Congé
     Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () { // Préfixe URL + Nom

        // Gestion des Types de Congé
        Route::resource('leave-types', LeaveTypeController::class)
              ->parameters(['leave-types' => 'leaveType']); // Le nommage est géré par le groupe

        // Gestion des Utilisateurs
        Route::resource('users', UserController::class)
              ->except(['show']); // On garde create/store, on enlève show qui redirige vers edit

        // Gestion des paramètres de l'entreprise
        Route::get('/settings/company', [CompanySettingController::class, 'index'])->name('settings.company');
        Route::put('/settings/company', [CompanySettingController::class, 'update'])->name('settings.company.update');

      Route::resource('absences', AbsenceController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index'); 
     Route::resource('vehicles', VehicleController::class);
      Route::resource('evaluation-criteria', EvaluationCriterionController::class)
          ->parameters(['evaluation-criteria' => 'criterion']); // Renomme paramètre
      Route::get('/reports/export/employees', [ReportController::class, 'exportEmployeesCsv'])
          ->name('reports.export.employees');
    });


}); // Fin groupe auth

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/company-settings', [CompanySettingController::class, 'edit'])->name('company-settings.edit');
    Route::put('/company-settings', [CompanySettingController::class, 'update'])->name('company-settings.update');
});

require __DIR__.'/auth.php';
