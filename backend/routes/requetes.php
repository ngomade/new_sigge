<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\requetes\BureauControllerApi;
use App\Http\Controllers\requetes\BureauController;

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


// Alternative recommandée pour éviter les conflits :
// Route::prefix('bureaux')->name('bureaux.')->group(function () {
//     Route::get('search', [BureauController::class, 'search'])->name('search');
//     Route::get('{code_bureau}/sous-bureaux', [BureauController::class, 'sousBureaux'])->name('sous-bureaux');
//     Route::get('{code_bureau}/bureau-parents', [BureauController::class, 'bureauParents'])->name('bureau-parents');
//     Route::get('{code_bureau}/documents', [BureauController::class, 'documents'])->name('documents');
//     Route::get('{code_bureau}/presentations', [BureauController::class, 'presentations'])->name('presentations');
// });
// 
// Route::resource('bureaux', BureauController::class)->parameters([
//     'bureaux' => 'code_bureau'
// ]);