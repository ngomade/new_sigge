<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\requetes\BureauControllerApi;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\requetes\RequetteController;
use App\Http\Controllers\requetes\AdminRequeteController;
use App\Http\Controllers\requetes\AffectationPersonnelController;
use App\Http\Controllers\requetes\AffectationPersonnelControllerApi;
use App\Http\Controllers\requetes\AdminRequetteControllerApi;
use App\Http\Controllers\requetes\RequetteControllerApi;
use App\Http\Controllers\requetes\CategoryController;


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



// use App\Http\Controllers\requetes\AffectationController;

// Route::resource('affectations', AffectationController::class);


Route::prefix('affectations')->group(function () {
    // CRUD de base
    Route::get('/', [AffectationPersonnelControllerApi::class, 'index']); // Liste toutes les affectations
    Route::post('/', [AffectationPersonnelControllerApi::class, 'store']); // Créer une affectation
    Route::get('/personnel/{code_pers}', [AffectationPersonnelControllerApi::class, 'show']); // Affectations d'un personnel
    Route::get('/personnel/{code_pers}/role/{id_role}', [AffectationPersonnelControllerApi::class, 'showAffectation']); // Affectation spécifique
    Route::put('/personnel/{code_pers}/role/{id_role}', [AffectationPersonnelControllerApi::class, 'update']); // Modifier affectation
    Route::delete('/personnel/{code_pers}/role/{id_role}', [AffectationPersonnelControllerApi::class, 'destroy']); // Supprimer affectation


});

// routes pour web


Route::middleware('web')->group(function () {
    Route::resource('requetes', RequetteController::class, ['parameters' => ['requetes' => 'code_requete']]);


    Route::resource('affectation', AffectationPersonnelController::class);

    // Routes de ressource APRÈS les routes personnalisées
    Route::resource('bureaux', BureauController::class)->parameters([
        'bureaux' => 'code_bureau'
    ]);
});

Route::delete('requetes/fichiers/{id_fichier}', [RequetteController::class, 'deleteFichier'])->name('requetes.deleteFichier');
Route::get('requetes/fichiers/{id_fichier}/download', [RequetteController::class, 'downloadFichier'])->name('requetes.downloadFichier');

Route::prefix('api/requete')->group(function () {
    Route::get('/', [RequetteControllerApi::class, 'index']);
    Route::post('/', [RequetteControllerApi::class, 'store']);
    Route::get('/{code_requete}', [RequetteControllerApi::class, 'show']);
    Route::put('/{code_requete}', [RequetteControllerApi::class, 'update']);
    Route::delete('/{code_requete}', [RequetteControllerApi::class, 'destroy']);
});

Route::prefix('api/admin/requete')->group(function () {
    Route::get('/', [AdminRequetteControllerApi::class, 'index']);
    Route::get('/{code_requete}', [AdminRequetteControllerApi::class, 'show']);
    Route::put('/{code_requete}/status', [AdminRequetteControllerApi::class, 'updateStatus']);
    Route::post('/{code_requete}/assign', [AdminRequetteControllerApi::class, 'assign']);
    Route::post('/{code_requete}/response', [AdminRequetteControllerApi::class, 'addResponse']);
});

Route::prefix('admin/requetes')->name('admin.requetes.')->group(function () {

    Route::get('/', [AdminRequeteController::class, 'index'])->name('index');
    Route::get('/{code_requete}', [AdminRequeteController::class, 'show'])->name('show');
    Route::put('/{code_requete}/status', [AdminRequeteController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{code_requete}/assign', [AdminRequeteController::class, 'assign'])->name('assign');
    Route::post('/{code_requete}/response', [AdminRequeteController::class, 'addResponse'])->name('addResponse');
    // Route pour les statistiques des requêtes
    // Routes pour les statistiques côté admin
    Route::get('admin/requetes/statistiques', [AdminRequeteController::class, 'statistiques'])->name('statistiques');
    Route::get('/categorie', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categorie', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categorie/{code_cat}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categorie/{code_cat}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
