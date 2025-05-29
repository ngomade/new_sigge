<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Concours\SiteEtudeController;
use App\Http\Controllers\Concours\CandidatController;
use App\Http\Controllers\Concours\CompteController;
use App\Http\Controllers\Concours\SessionConcourController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes pour SiteEtude
Route::prefix('sites-etude')->group(function () {
    Route::get('/', [SiteEtudeController::class, 'index']);
    Route::post('/', [SiteEtudeController::class, 'store']);
    Route::get('/{code_site}', [SiteEtudeController::class, 'show']);
    Route::put('/{code_site}', [SiteEtudeController::class, 'update']);
    Route::delete('/{code_site}', [SiteEtudeController::class, 'destroy']);
    Route::get('/{code_site}/candidats', [SiteEtudeController::class, 'getCandidats']);
});

// Routes pour Candidat
Route::prefix('candidats')->group(function () {
    Route::get('/', [CandidatController::class, 'index']);
    Route::post('/', [CandidatController::class, 'store']);
    Route::get('/{ca_code}', [CandidatController::class, 'show']);
    Route::put('/{ca_code}', [CandidatController::class, 'update']);
    Route::delete('/{ca_code}', [CandidatController::class, 'destroy']);
    Route::get('/statistics', [CandidatController::class, 'statistics']);
});

// Routes pour Compte
Route::prefix('comptes')->group(function () {
    Route::get('/', [CompteController::class, 'index']);
    Route::post('/', [CompteController::class, 'store']);
    Route::get('/{ca_num_recu}', [CompteController::class, 'show']);
    Route::put('/{ca_num_recu}', [CompteController::class, 'update']);
    Route::delete('/{ca_num_recu}', [CompteController::class, 'destroy']);
    Route::post('/{ca_num_recu}/reset-password', [CompteController::class, 'resetPassword']);
    Route::post('/login', [CompteController::class, 'login']);
});

// Routes pour SessionConcour
Route::prefix('sessions-concours')->group(function () {
    Route::get('/', [SessionConcourController::class, 'index']);
    Route::post('/', [SessionConcourController::class, 'store']);
    Route::get('/{id}', [SessionConcourController::class, 'show']);
    Route::put('/{id}', [SessionConcourController::class, 'update']);
    Route::delete('/{id}', [SessionConcourController::class, 'destroy']);
}); 