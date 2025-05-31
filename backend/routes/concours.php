<?php

use App\Http\Controllers\concours\auth\AuthController;
use App\Http\Controllers\concours\auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\concours\CandidatControllerApi;
use App\Http\Controllers\concours\CandidatEcoleControllerApi;
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


Route::get("check-token", [AuthController::class, 'checkToken']);
//Route d'authentification
Route::middleware(["guest.sanctum", "throttle:5,1"])->group(function () {
    Route::post("login", [AuthController::class, 'login']);
    Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
});
Route::middleware("auth:sanctum")->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });
    Route::get("logout", [AuthController::class, 'logout']);
    Route::get("refresh-token", [AuthController::class, 'refresh']);
});

//Route principale
// Routes publiques (sans authentification pour les tests)

    // Routes pour les candidats
    Route::apiResource('candidats', CandidatControllerApi::class);
    Route::get('candidats/filiere/{filiere_code}', [CandidatControllerApi::class, 'byFiliere']);
    Route::get('candidats/site/{code_site}', [CandidatControllerApi::class, 'bySite']);
    Route::post('candidats/search', [CandidatControllerApi::class, 'search']);

    // Routes pour les relations candidat-école
    Route::apiResource('candidat-ecoles', CandidatEcoleControllerApi::class);
    Route::apiResource('candidat_ecole', CandidatEcoleControllerApi::class);

    // Routes pour les comptes
    Route::apiResource('comptes', CompteControllerApi::class);
    Route::post('comptes/login', [CompteControllerApi::class, 'login']);
    Route::put('comptes/{ca_num_recu}/change-password', [CompteControllerApi::class, 'changePassword']);
    Route::get('comptes/candidat/{ca_code}', [CompteControllerApi::class, 'byCandidat']);

    // Routes pour les sessions de concours
    Route::apiResource('sessions', SessionconcourControllerApi::class);
    Route::get('sessions/active', [SessionconcourControllerApi::class, 'active']);
    Route::get('sessions/year/{year}', [SessionconcourControllerApi::class, 'byYear']);
    Route::get('sessions/{id}/statistics', [SessionconcourControllerApi::class, 'statistics']);
    Route::get('sessions/upcoming', [SessionconcourControllerApi::class, 'upcoming']);
    Route::get('sessions/past', [SessionconcourControllerApi::class, 'past']);

    // Routes pour les sites d'étude
    Route::apiResource('sites', SiteEtudeControllerApi::class);
    Route::get('sites/{code_site}/candidats', [SiteEtudeControllerApi::class, 'candidats']);
    Route::get('sites/{code_site}/statistics', [SiteEtudeControllerApi::class, 'statistics']);
    Route::post('sites/search', [SiteEtudeControllerApi::class, 'search']);

    // Routes pour les écoles
    Route::apiResource('ecole', EcoleControllerApi::class);

    // Routes pour les centres de dépôt
    Route::apiResource('centre_depot', CentreDepotControllerApi::class);

    // Routes pour les centres d'examen
    Route::apiResource('centre_examen', CentreExamenControllerApi::class);

    // Routes pour les dossiers
    Route::apiResource('dossier', DossierControllerApi::class);

    // Routes pour les sites de composition
    Route::apiResource('site_composition', SiteCompositionControllerApi::class);

    // Routes pour les compositions
    Route::apiResource('composition', CompositionControllerApi::class);

    // Routes pour les éléments d'école
    Route::apiResource('ecole_element', EcoleElementControllerApi::class);
});
