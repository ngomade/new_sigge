<?php

use App\Http\Controllers\concours\auth\AuthController;
use App\Http\Controllers\concours\auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\concours\CandidatControllerApi;
use App\Http\Controllers\concours\CompteControllerApi;
use App\Http\Controllers\concours\SessionconcourControllerApi;
use App\Http\Controllers\concours\SiteEtudeControllerApi;
use App\Http\Controllers\concours\EcoleControllerApi;
use App\Http\Controllers\concours\SiteCompositionControllerApi;
use App\Http\Controllers\concours\DossierControllerApi;
use App\Http\Controllers\concours\CentreExamenControllerApi;
use App\Http\Controllers\concours\CentreDepotControllerApi;
use App\Http\Controllers\concours\CompositionControllerApi;
use App\Http\Controllers\concours\EcoleElementControllerApi;
use App\Http\Controllers\concours\FiliereControllerAPI;
use App\Http\Controllers\concours\FiliereDiplomeControllerAPI;
use App\Http\Controllers\concours\PersonnelControllerApi;
use App\Http\Controllers\concours\SlideControllerApi;

/*
|--------------------------------------------------------------------------
| Routes API pour le module Concours
|--------------------------------------------------------------------------
*/

// Routes pour verifier le token
Route::get("check-token", [AuthController::class, 'checkToken']);

// Routes d'authentification
Route::group(['prefix' => 'auth', 'middleware' => "guest.sanctum"], function () {
    Route::post("login", [AuthController::class, 'login']);
    Route::post('forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);
    Route::get("logout", [AuthController::class, 'logout']);
    Route::get("refresh-token", [AuthController::class, 'refresh']);
});

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    /*
    |--------------------------------------------------------------------------
    | Routes pour la gestion des candidats
    |--------------------------------------------------------------------------
    */
    Route::prefix('candidats')->group(function () {
        Route::get("stats", [CandidatControllerApi::class, 'statCandidat']);
        Route::get("send-general-email", [CandidatControllerApi::class, 'sendGeneralMail']);
        Route::get('filiere/{filiere_code}', [CandidatControllerApi::class, 'byFiliere']);
        Route::get('site/{code_site}', [CandidatControllerApi::class, 'bySite']);
        Route::post('search', [CandidatControllerApi::class, 'search']);
    });
    Route::apiResource('candidat', CandidatControllerApi::class);

    /*
    |--------------------------------------------------------------------------
    | Routes pour la gestion des comptes
    |--------------------------------------------------------------------------
    */
    Route::prefix('comptes')->group(function () {
        Route::get('candidat/{ca_code}', [CompteControllerApi::class, 'byCandidat']);
        Route::get("download-recu/{ca_num_recu}", [CompteControllerApi::class, 'showRecu']);
        Route::get("stats", [CompteControllerApi::class, 'statsCompte']);
    });
    Route::apiResource('comptes', CompteControllerApi::class);

    /*
    |--------------------------------------------------------------------------
    | Routes pour la gestion des sessions de concours
    |--------------------------------------------------------------------------
    */
    Route::prefix('sessions')->group(function () {
        Route::get('active', [SessionconcourControllerApi::class, 'active']);
        Route::get('year/{year}', [SessionconcourControllerApi::class, 'byYear']);
        Route::get('{id}/stats', [SessionconcourControllerApi::class, 'statistics']);
        Route::get('upcoming', [SessionconcourControllerApi::class, 'upcoming']);
        Route::get('past', [SessionconcourControllerApi::class, 'past']);
    });
    Route::apiResource('sessions', SessionconcourControllerApi::class);

    /*
    |--------------------------------------------------------------------------
    | Routes pour la gestion des sites d'étude
    |--------------------------------------------------------------------------
    */
    Route::prefix('sites')->group(function () {
        Route::get('{code_site}/stats', [SiteEtudeControllerApi::class, 'statistics']);
        Route::post('search', [SiteEtudeControllerApi::class, 'search']);
    });
    Route::apiResource('sites', SiteEtudeControllerApi::class);

    /*
    |--------------------------------------------------------------------------
    | Routes pour la gestion des écoles
    |--------------------------------------------------------------------------
    */
    Route::apiResource('ecole', EcoleControllerApi::class);

    /*
    |--------------------------------------------------------------------------
    | Routes pour les centres (dépôt et examen)
    |--------------------------------------------------------------------------
    */
    Route::apiResource('centre_depot', CentreDepotControllerApi::class);
    Route::apiResource('centre_examen', CentreExamenControllerApi::class);

    /*
    |--------------------------------------------------------------------------
    | Routes pour les dossiers et sites de composition
    |--------------------------------------------------------------------------
    */
    Route::apiResource('dossier', DossierControllerApi::class);
    Route::apiResource('site_composition', SiteCompositionControllerApi::class);

    /*
    |--------------------------------------------------------------------------
    | Routes pour la gestion des filières
    |--------------------------------------------------------------------------
    */
    Route::apiResource('filiere', FiliereControllerAPI::class);
    Route::apiResource('filiere_diplome', FiliereDiplomeControllerAPI::class);
      // Diplome attachment routes
      Route::post('/{filiereCode}/attach-diplome', [FiliereControllerAPI::class, 'attachDiplome']);
      Route::post('/{filiereCode}/detach-diplome', [FiliereControllerAPI::class, 'detachDiplome']);
      
      //routes personnel
      Route::apiResource('personnel',PersonnelControllerApi::class);
      //routes slide
      Route::apiResource('slide',SlideControllerApi::class);
      
      // Additional routes
    Route::get('/by-filiere/{filiereCode}', [FiliereDiplomeControllerAPI::class, 'byFiliere']);
    Route::get('/by-diplome/{diplomeCode}', [FiliereDiplomeControllerAPI::class, 'byDiplome']);

});
