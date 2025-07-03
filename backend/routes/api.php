<?php

use App\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\AffectationController;

// Route pour récupérer la liste des rôles
Route::get('/roles', function() {
    return Role::select('id', 'name')->get();
})->name('api.roles.index');

// Routes pour la gestion du personnel dans les bureaux
Route::prefix('bureau')->group(function () {
    // Recherche de personnel
    Route::get('/personnel/search', [AffectationController::class, 'searchPersonnel'])->name('api.personnel.search');

    // Affecter du personnel à un bureau
    Route::post('/affecter-personnel', [BureauController::class, 'affecterPersonnel'])->name('api.bureau.affecter-personnel');

    // Affecter plusieurs rôles à plusieurs personnels
    Route::post('/affecter-personnel-multiple', [AffectationController::class, 'affecterPersonnelMultiple'])->name('api.bureau.affecter-personnel-multiple');

    // Récupérer le personnel d'un bureau
    Route::get('/{code}/personnel', [AffectationController::class, 'getPersonnelBureau'])->name('api.bureau.personnel');

    // Activer/Désactiver un rôle
    Route::post('/toggle-role', [AffectationController::class, 'toggleRole'])->name('api.bureau.toggle-role');

    // Supprimer une affectation
    Route::post('/supprimer-affectation', [AffectationController::class, 'supprimerAffectation'])->name('api.bureau.supprimer-affectation');

    // Récupérer le code du bureau par type
    Route::get('/{type}/code', [BureauController::class, 'getBureauCodeByType'])->name('api.bureau.code-by-type');

    // Récupérer les statistiques
    Route::get('/{code}/stats', [AffectationController::class, 'getStats'])->name('api.bureau.stats');
});

// Intégration des routes du concours avec un préfixe
Route::prefix('concours')->group(function () {
    require __DIR__.'/concours.php';
});

Route::prefix('notes')->group(function () {
    require __DIR__.'/notes.php';
});

Route::prefix('requetes')->group(function () {
    require __DIR__.'/requetes.php';
});
// Routes du module Laboratoire
