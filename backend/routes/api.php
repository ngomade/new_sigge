<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BureauController;

// Route pour récupérer la liste des rôles
Route::get('/roles', function() {
    return \App\Models\Role::select('id', 'name')->get();
})->name('api.roles.index');

// Routes pour la gestion du personnel dans les bureaux
Route::prefix('bureau')->group(function () {
    // Recherche de personnel
    Route::get('/personnel/search', [BureauController::class, 'searchPersonnel'])->name('api.personnel.search');
    
    // Affecter du personnel à un bureau
    Route::post('/affecter-personnel', [BureauController::class, 'affecterPersonnel'])->name('api.bureau.affecter-personnel');
    
    // Récupérer le personnel d'un bureau
    Route::get('/{code}/personnel', [BureauController::class, 'getPersonnelBureau'])->name('api.bureau.personnel');

    // Désactiver un rôle
    Route::post('/desactiver-role', [BureauController::class, 'desactiverRole'])->name('api.bureau.desactiver-role');

    // Récupérer le code du bureau par type
    Route::get('/{type}/code', [BureauController::class, 'getBureauCodeByType'])->name('api.bureau.code-by-type');
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
