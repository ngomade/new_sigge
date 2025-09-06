<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\notes\AnneescolaireController;
use App\Http\Controllers\notes\ClasseController;
use App\Http\Controllers\notes\NiveauController;
use App\Http\Controllers\notes\AssignationController;
use App\Http\Controllers\notes\EvaluationController;
use App\Http\Controllers\notes\SessionExamenController;
use App\Http\Controllers\notes\ExamenController;
use App\Http\Controllers\notes\PeriodeController;
use App\Http\Controllers\notes\RessourceController;

// Années scolaires routes
Route::prefix('annees')->group(function () {
    Route::get('/', [AnneescolaireController::class, 'index'])->name('annees.index');
    Route::get('/create', [AnneescolaireController::class, 'create'])->name('annees.create');
    Route::post('/', [AnneescolaireController::class, 'store'])->name('annees.store');
    Route::get('/{code_annee}', [AnneescolaireController::class, 'show'])->name('annees.show');
    Route::get('/{code_annee}/edit', [AnneescolaireController::class, 'edit'])->name('annees.edit');
    Route::put('/{code_annee}', [AnneescolaireController::class, 'update'])->name('annees.update');
    Route::delete('/{code_annee}', [AnneescolaireController::class, 'destroy'])->name('annees.destroy');
});

// Classes routes
Route::prefix('classes')->group(function () {
    Route::get('/', [ClasseController::class, 'index'])->name('classes.index');
    Route::get('/create', [ClasseController::class, 'create'])->name('classes.create');
    Route::post('/', [ClasseController::class, 'store'])->name('classes.store');
    Route::get('/{code_class}', [ClasseController::class, 'show'])->name('classes.show');
    Route::get('/{code_class}/edit', [ClasseController::class, 'edit'])->name('classes.edit');
    Route::put('/{code_class}', [ClasseController::class, 'update'])->name('classes.update');
    Route::delete('/{code_class}', [ClasseController::class, 'destroy'])->name('classes.destroy');
});

// Niveaux routes
Route::prefix('niveaux')->group(function () {
    Route::get('/', [NiveauController::class, 'index'])->name('niveaux.index');
    Route::get('/create', [NiveauController::class, 'create'])->name('niveaux.create');
    Route::post('/', [NiveauController::class, 'store'])->name('niveaux.store');
    Route::get('/{code_niveau}', [NiveauController::class, 'show'])->name('niveaux.show');
    Route::get('/{code_niveau}/edit', [NiveauController::class, 'edit'])->name('niveaux.edit');
    Route::put('/{code_niveau}', [NiveauController::class, 'update'])->name('niveaux.update');
    Route::delete('/{code_niveau}', [NiveauController::class, 'destroy'])->name('niveaux.destroy');
});

// Assignations routes
Route::prefix('assignations')->group(function () {
    Route::get('/', [AssignationController::class, 'index'])->name('assignations.index');
    Route::get('/create', [AssignationController::class, 'create'])->name('assignations.create');
    Route::post('/', [AssignationController::class, 'store'])->name('assignations.store');
    Route::get('/{code_ass}', [AssignationController::class, 'show'])->name('assignations.show');
    Route::get('/{code_ass}/edit', [AssignationController::class, 'edit'])->name('assignations.edit');
    Route::put('/{code_ass}', [AssignationController::class, 'update'])->name('assignations.update');
    Route::delete('/{code_ass}', [AssignationController::class, 'destroy'])->name('assignations.destroy');
    
    // Additional routes for assignation
    Route::post('/mass-assign', [AssignationController::class, 'massAssign'])->name('assignations.massAssign');
    Route::get('/get-ecs-by-classe/{code_class}', [AssignationController::class, 'getEcsByClasse'])->name('assignations.getEcsByClasse');
    Route::get('/get-assignations-by-personnel/{code_pers}', [AssignationController::class, 'getAssignationsByPersonnel'])->name('assignations.getAssignationsByPersonnel');
});

// Evaluations routes
Route::prefix('evaluations')->group(function () {
    Route::get('/', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/create', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/{code_ec}/{code_examen}/{code_user}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::get('/{code_ec}/{code_examen}/edit', [EvaluationController::class, 'edit'])->name('evaluations.edit');
    Route::put('/{code_ec}/{code_examen}', [EvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('/{code_ec}/{code_examen}', [EvaluationController::class, 'destroy'])->name('evaluations.destroy');
    
    // Additional routes for evaluations
    Route::get('/moyennes', [EvaluationController::class, 'moyennes'])->name('evaluations.moyennes');
    Route::get('/get-etudiants-by-ec/{code_ec}', [EvaluationController::class, 'getEtudiantsByEcApi'])->name('evaluations.getEtudiantsByEc');
    Route::get('/export', [EvaluationController::class, 'export'])->name('evaluations.export');
});

// Sessions d'examen routes
Route::prefix('sessions-examen')->group(function () {
    Route::get('/', [SessionExamenController::class, 'index'])->name('sessionsExamen.index');
    Route::get('/create', [SessionExamenController::class, 'create'])->name('sessionsExamen.create');
    Route::post('/', [SessionExamenController::class, 'store'])->name('sessionsExamen.store');
    Route::get('/{code_session}', [SessionExamenController::class, 'show'])->name('sessionsExamen.show');
    Route::get('/{code_session}/edit', [SessionExamenController::class, 'edit'])->name('sessionsExamen.edit');
    Route::put('/{code_session}', [SessionExamenController::class, 'update'])->name('sessionsExamen.update');
    Route::delete('/{code_session}', [SessionExamenController::class, 'destroy'])->name('sessionsExamen.destroy');
    
    // Additional routes for sessions d'examen
    Route::post('/{code_session}/toggle-status', [SessionExamenController::class, 'toggleStatus'])->name('sessionsExamen.toggleStatus');
    Route::get('/get-sessions-by-annee/{code_annee}', [SessionExamenController::class, 'getSessionsByAnnee'])->name('sessionsExamen.getSessionsByAnnee');
    Route::post('/{code_session}/duplicate', [SessionExamenController::class, 'duplicate'])->name('sessionsExamen.duplicate');
});

// Examens routes
Route::prefix('examens')->group(function () {
    Route::get('/', [ExamenController::class, 'index'])->name('examens.index');
    Route::get('/create', [ExamenController::class, 'create'])->name('examens.create');
    Route::post('/', [ExamenController::class, 'store'])->name('examens.store');
    Route::get('/{code_examen}', [ExamenController::class, 'show'])->name('examens.show');
    Route::get('/{code_examen}/edit', [ExamenController::class, 'edit'])->name('examens.edit');
    Route::put('/{code_examen}', [ExamenController::class, 'update'])->name('examens.update');
    Route::delete('/{code_examen}', [ExamenController::class, 'destroy'])->name('examens.destroy');
    
    // Additional routes for examens
    Route::get('/{code_examen}/planifier', [ExamenController::class, 'planifier'])->name('examens.planifier');
    Route::post('/{code_examen}/planifier', [ExamenController::class, 'storePlanification'])->name('examens.storePlanification');
    Route::get('/get-examens-by-session/{code_session}', [ExamenController::class, 'getExamensBySession'])->name('examens.getExamensBySession');
    Route::get('/get-salles-disponibles', [ExamenController::class, 'getSallesDisponibles'])->name('examens.getSallesDisponibles');
    Route::get('/{code_examen}/export/{format?}', [ExamenController::class, 'exportPlanning'])->name('examens.exportPlanning');
});

// Périodes routes
Route::prefix('periodes')->group(function () {
    Route::get('/', [PeriodeController::class, 'index'])->name('periodes.index');
    Route::get('/create', [PeriodeController::class, 'create'])->name('periodes.create');
    Route::post('/', [PeriodeController::class, 'store'])->name('periodes.store');
    Route::get('/{code_salle}/{code_ec}', [PeriodeController::class, 'show'])->name('periodes.show');
    Route::get('/{code_salle}/{code_ec}/edit', [PeriodeController::class, 'edit'])->name('periodes.edit');
    Route::put('/{code_salle}/{code_ec}', [PeriodeController::class, 'update'])->name('periodes.update');
    Route::delete('/{code_salle}/{code_ec}', [PeriodeController::class, 'destroy'])->name('periodes.destroy');
    
    // Additional routes for périodes
    Route::get('/get-periodes-by-date-range', [PeriodeController::class, 'getPeriodesByDateRange'])->name('periodes.getPeriodesByDateRange');
    Route::get('/check-disponibilite-salle', [PeriodeController::class, 'checkDisponibiliteSalle'])->name('periodes.checkDisponibiliteSalle');
    Route::post('/mass-delete', [PeriodeController::class, 'massDelete'])->name('periodes.massDelete');
});

// Ressources routes
Route::prefix('ressources')->group(function () {
    // Documents routes
    Route::get('/documents', [RessourceController::class, 'indexDocuments'])->name('ressources.documents.index');
    Route::get('/documents/create', [RessourceController::class, 'createDocument'])->name('ressources.documents.create');
    Route::post('/documents', [RessourceController::class, 'storeDocument'])->name('ressources.documents.store');
    Route::get('/documents/{id}', [RessourceController::class, 'showDocument'])->name('ressources.documents.show');
    Route::get('/documents/{id}/edit', [RessourceController::class, 'editDocument'])->name('ressources.documents.edit');
    Route::put('/documents/{id}', [RessourceController::class, 'updateDocument'])->name('ressources.documents.update');
    Route::delete('/documents/{id}', [RessourceController::class, 'destroyDocument'])->name('ressources.documents.destroy');
    Route::get('/documents/{id}/download', [RessourceController::class, 'downloadDocument'])->name('ressources.documents.download');
    Route::get('/documents/session/{codeSession}', [RessourceController::class, 'getDocumentsBySession'])->name('ressources.documents.getBySession');
    
    // Salles routes
    Route::get('/salles', [RessourceController::class, 'indexSalles'])->name('ressources.salles.index');
    Route::get('/salles/create', [RessourceController::class, 'createSalle'])->name('ressources.salles.create');
    Route::post('/salles', [RessourceController::class, 'storeSalle'])->name('ressources.salles.store');
    Route::get('/salles/{codeSalle}', [RessourceController::class, 'showSalle'])->name('ressources.salles.show');
    Route::get('/salles/{codeSalle}/edit', [RessourceController::class, 'editSalle'])->name('ressources.salles.edit');
    Route::put('/salles/{codeSalle}', [RessourceController::class, 'updateSalle'])->name('ressources.salles.update');
    Route::delete('/salles/{codeSalle}', [RessourceController::class, 'destroySalle'])->name('ressources.salles.destroy');
    Route::get('/salles-disponibles', [RessourceController::class, 'getSallesDisponibles'])->name('ressources.salles.disponibles');
    Route::get('/salles/{codeSalle}/verifier-disponibilite', [RessourceController::class, 'verifierDisponibiliteSalle'])->name('ressources.salles.verifierDisponibilite');
    
    // Statistiques
    Route::get('/statistiques', [RessourceController::class, 'getStatistiquesRessources'])->name('ressources.statistiques');
});

// Consultation des notes routes
Route::prefix('consultation-note')->group(function () {
    Route::get('/', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.index');
    })->name('consultation.note.index');
    
    Route::get('/student-notes', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.student_notes');
    })->name('consultation.note.studentNotes');
    
    Route::get('/student-semester-notes', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.student_semester_notes');
    })->name('consultation.note.studentSemesterNotes');
    
    Route::get('/student-report-card', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.student_report_card');
    })->name('consultation.note.studentReportCard');
    
    Route::get('/student-transcript', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.student_transcript');
    })->name('consultation.note.studentTranscript');
    
    Route::get('/class-results', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.class_results');
    })->name('consultation.note.classResults');
    
    Route::get('/level-results', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.level_results');
    })->name('consultation.note.levelResults');
    
    Route::get('/field-results', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.field_results');
    })->name('consultation.note.fieldResults');
    
    Route::get('/semester-ranking', function () {
        return view('sige_app.backend.gestion_notes.consultation_note.semester_ranking');
    })->name('consultation.note.semesterRanking');
});


