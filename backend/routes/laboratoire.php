<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Labo\LaboratoireController;
use App\Http\Controllers\Labo\ProjetLaboController;
use App\Http\Controllers\Labo\PersLabController;
use App\Http\Controllers\Labo\EquipementsController;
use App\Http\Controllers\Labo\PublicationController;
use App\Http\Controllers\Labo\UserExterneController;
use App\Http\Controllers\Labo\RoleLaboController;
use App\Http\Controllers\Labo\PublicLaboratoireController;
use App\Http\Controllers\Labo\CandidatureController;

// Routes publiques pour les laboratoires
Route::prefix('laboratoires')->name('laboratoires.')->group(function () {
    // Landing page publique d'un laboratoire
    Route::get('/{code_lab}', [PublicLaboratoireController::class, 'show'])->name('show');

    // Page de candidature
    Route::get('/{code_lab}/candidature', [CandidatureController::class, 'create'])->name('candidature.create');
    Route::post('/{code_lab}/candidature', [CandidatureController::class, 'store'])->name('candidature.store');

    // Connexion au laboratoire
    Route::get('/{code_lab}/login', [PublicLaboratoireController::class, 'loginForm'])->name('login.form');
    Route::post('/{code_lab}/login', [PublicLaboratoireController::class, 'login'])->name('login');
    Route::post('/{code_lab}/logout', [PublicLaboratoireController::class, 'logout'])->name('logout');

    // Routes protégées pour les membres connectés
    Route::middleware('laboratoire.auth')->group(function () {
        // Espace membre du laboratoire
        Route::get('/{code_lab}/espace-membre', [PublicLaboratoireController::class, 'espaceMembre'])->name('espace.membre');

        // Profil utilisateur
        Route::get('/{code_lab}/profil', [PublicLaboratoireController::class, 'profil'])->name('profil');
        Route::put('/{code_lab}/profil', [PublicLaboratoireController::class, 'updateProfil'])->name('profil.update');
    });
});

// Routes pour le module Laboratoire
Route::prefix('labo')->name('labo.')->group(function () {

    // Laboratoires
    Route::resource('laboratoires', LaboratoireController::class);

    // Projets
    Route::resource('projets', ProjetLaboController::class);

    // Membres internes
    Route::resource('membres', PersLabController::class);

    // Équipements
    Route::resource('equipements', EquipementsController::class);
    Route::get('equipements/{id}/reserver', [EquipementsController::class, 'showReservationForm'])
        ->name('equipements.reserver');
    Route::post('equipements/{id}/reserver', [EquipementsController::class, 'storeReservation'])
        ->name('equipements.reservation.store');

    // Publications
    Route::resource('publications', PublicationController::class);

    // Utilisateurs externes
    Route::resource('externes', UserExterneController::class);

    // Rôles de laboratoire
    Route::resource('roles', RoleLaboController::class);

    // Gestion des candidatures (admin)
    Route::prefix('candidatures')->name('candidatures.')->group(function () {
        Route::get('/', [CandidatureController::class, 'index'])->name('index');
        Route::get('/{id}', [CandidatureController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [CandidatureController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [CandidatureController::class, 'reject'])->name('reject');
    });

    // Dashboard
    Route::get('dashboard', function () {
        $stats = [
            'laboratoires' => \App\Models\laboratoires\Laboratoire::count(),
            'projets' => \App\Models\laboratoires\ProjetLabo::count(),
            'membres' => \App\Models\laboratoires\PersLab::where('statut', 'actif')->count(),
            'equipements' => \App\Models\laboratoires\Equipements::count(),
            'publications' => \App\Models\laboratoires\Publication::count(),
            'externes' => \App\Models\laboratoires\UserExterne::where('statut', 'actif')->count()
        ];

        return view('sige_app.frontend.labo.dashboard', compact('stats'));
    })->name('dashboard');

    // Gestion des membres du laboratoire
    Route::prefix('laboratoires/{laboratoire}/membres')->name('laboratoires.membres.')->group(function () {
        Route::get('/', [LaboratoireController::class, 'membres'])->name('index');
        Route::get('/ajouter', [LaboratoireController::class, 'ajouterMembreForm'])->name('create');
        Route::post('/ajouter', [LaboratoireController::class, 'ajouterMembre'])->name('store');
        Route::get('/{membre}/modifier', [LaboratoireController::class, 'modifierMembreForm'])->name('edit');
        Route::put('/{membre}/modifier', [LaboratoireController::class, 'modifierMembre'])->name('update');
        Route::delete('/{membre}', [LaboratoireController::class, 'supprimerMembre'])->name('destroy');

        // Route AJAX pour récupérer les personnes par type
        Route::get('/get-persons', [LaboratoireController::class, 'getPersonsByType'])->name('get-persons');
    });
});

Route::get('/presentation_ufd_tsi',  [LaboratoireController::class, 'index']);
//Route::get('/presentation_labo/{id}',  [LaboratoireController::class, 'show']);
