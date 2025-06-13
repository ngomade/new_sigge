<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\requetes\BureauControllerApi;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\requetes\AffectationPersonnelController;
use App\Http\Controllers\requetes\AffectationPersonnelControllerApi;

Route::prefix('requete')->group(function () {
    // IMPORTANT: Routes personnalisées AVANT les routes de ressource
    Route::get('bureaux/search', [BureauControllerApi::class, 'search'])->name('api.bureaux.search');
    Route::get('bureaux/{code_bureau}/sous-bureaux', [BureauControllerApi::class, 'getSousBureaux'])->name('api.bureaux.sous-bureaux');
    Route::get('bureaux/{code_bureau}/bureau-parents', [BureauControllerApi::class, 'getBureauParents'])->name('api.bureaux.bureau-parents');
    
    // Routes de ressource APRÈS les routes personnalisées
    Route::apiResource('bureaux', BureauControllerApi::class)->parameters([
        'bureaux' => 'code_bureau'
    ]);
});


// Routes personnalisées AVANT les routes de ressource
Route::get('bureaux/search', [BureauController::class, 'search'])->name('bureaux.search');
Route::get('bureaux/{code_bureau}/sous-bureaux', [BureauController::class, 'sousBureaux'])->name('bureaux.sous-bureaux');
Route::get('bureaux/{code_bureau}/bureau-parents', [BureauController::class, 'bureauParents'])->name('bureaux.bureau-parents');
Route::get('bureaux/{code_bureau}/documents', [BureauController::class, 'documents'])->name('bureaux.documents');
Route::get('bureaux/{code_bureau}/presentations', [BureauController::class, 'presentations'])->name('bureaux.presentations');

// Routes de ressource APRÈS les routes personnalisées
Route::resource('bureaux', BureauController::class)->parameters([
    'bureaux' => 'code_bureau'
]);







// use App\Http\Controllers\requetes\AffectationController;

// Route::resource('affectations', AffectationController::class);

Route::resource('affectation', AffectationPersonnelController::class);

Route::prefix('affectations')->group(function () {
    // CRUD de base
    Route::get('/', [AffectationPersonnelControllerApi::class, 'index']); // Liste toutes les affectations
    Route::post('/', [AffectationPersonnelControllerApi::class, 'store']); // Créer une affectation
    Route::get('/personnel/{code_pers}', [AffectationPersonnelControllerApi::class, 'show']); // Affectations d'un personnel
    Route::get('/personnel/{code_pers}/role/{id_role}', [AffectationPersonnelControllerApi::class, 'showAffectation']); // Affectation spécifique
    Route::put('/personnel/{code_pers}/role/{id_role}', [AffectationPersonnelControllerApi::class, 'update']); // Modifier affectation
    Route::delete('/personnel/{code_pers}/role/{id_role}', [AffectationPersonnelControllerApi::class, 'destroy']); // Supprimer affectation

    
});

