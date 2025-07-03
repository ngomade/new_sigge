<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Labo\LaboratoireController;
use App\Http\Controllers\Labo\ProjetLaboController;
use App\Http\Controllers\Labo\PersLabController;
use App\Http\Controllers\Labo\EquipementsController;
use App\Http\Controllers\Labo\PublicationController;
use App\Http\Controllers\Labo\UserExterneController;

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
});


// use App\Http\Controllers\labo\LaboratoireController;
// use Illuminate\Support\Facades\Route;

// Route::get('/presentation_ufd_tsi',  [LaboratoireController::class, 'index']);
// Route::get('/presentation_labo/{id}',  [LaboratoireController::class, 'show']);
