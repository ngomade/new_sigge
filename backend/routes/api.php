<?php

use App\Http\Controllers\AffectationController;
use App\Http\Controllers\BureauController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

// Route pour récupérer la liste des rôles
Route::get('/roles', function () {
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
Route::prefix('labo')->group(function () {
    // Récupérer les personnes selon le type avec recherche
    Route::get('/personnes/{type}', function ($type) {
        $search = request('search', '');
        $limit = request('limit', 20);

        switch ($type) {
            case 'personnel':
                $query = \App\Models\Personnel::select('code_pers as id', 'nom_pers', 'prenom_pers');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom_pers', 'LIKE', "%{$search}%")
                            ->orWhere('prenom_pers', 'LIKE', "%{$search}%")
                            ->orWhere('code_pers', 'LIKE', "%{$search}%");
                    });
                }

                return $query->limit($limit)->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nom' => $item->nom_pers,
                        'prenom' => $item->prenom_pers,
                        'display' => $item->nom_pers.' '.$item->prenom_pers,
                    ];
                });

            case 'users':
                $query = \App\Models\Users::select('code_user as id', 'nom_user', 'prenom_user');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom_user', 'LIKE', "%{$search}%")
                            ->orWhere('prenom_user', 'LIKE', "%{$search}%")
                            ->orWhere('code_user', 'LIKE', "%{$search}%");
                    });
                }

                return $query->limit($limit)->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nom' => $item->nom_user,
                        'prenom' => $item->prenom_user,
                        'display' => $item->nom_user.' '.$item->prenom_user,
                    ];
                });

            case 'user_externe':
                $query = \App\Models\laboratoires\UserExterne::select('id_user_ext as id', 'nom_user_ext', 'prenom_user_ext');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom_user_ext', 'LIKE', "%{$search}%")
                            ->orWhere('prenom_user_ext', 'LIKE', "%{$search}%")
                            ->orWhere('id_user_ext', 'LIKE', "%{$search}%");
                    });
                }

                return $query->limit($limit)->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nom' => $item->nom_user_ext,
                        'prenom' => $item->prenom_user_ext,
                        'display' => $item->nom_user_ext.' '.$item->prenom_user_ext,
                    ];
                });

            default:
                return response()->json(['error' => 'Type invalide'], 400);
        }
    })->name('api.labo.personnes');
});
