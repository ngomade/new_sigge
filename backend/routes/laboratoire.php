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
use App\Http\Controllers\Labo\AdminLaboratoireController;

// Routes publiques pour les laboratoires
Route::prefix('laboratoires')->name('laboratoires.')->group(function () {
    // Route pour rafraîchir le token CSRF
    Route::get('/csrf-token', function () {
        return response()->json(['token' => csrf_token()]);
    })->name('csrf-token');

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
    Route::resource('laboratoires', LaboratoireController::class)->middleware('laboratoire.permission:dashboard.view');

    // Projets
    Route::resource('projets', ProjetLaboController::class)->middleware([
        'index' => 'laboratoire.permission:projets.view',
        'create' => 'laboratoire.permission:projets.create',
        'store' => 'laboratoire.permission:projets.create',
        'edit' => 'laboratoire.permission:projets.edit',
        'update' => 'laboratoire.permission:projets.edit',
        'destroy' => 'laboratoire.permission:projets.delete',
        'show' => 'laboratoire.permission:projets.view',
    ]);

    // Membres internes
    Route::resource('membres', PersLabController::class)->middleware([
        'index' => 'laboratoire.permission:membres.view',
        'create' => 'laboratoire.permission:membres.create',
        'store' => 'laboratoire.permission:membres.create',
        'edit' => 'laboratoire.permission:membres.edit',
        'update' => 'laboratoire.permission:membres.edit',
        'destroy' => 'laboratoire.permission:membres.delete',
        'show' => 'laboratoire.permission:membres.view',
    ]);

    // Équipements
    Route::resource('equipements', EquipementsController::class)->middleware([
        'index' => 'laboratoire.permission:equipements.view',
        'create' => 'laboratoire.permission:equipements.create',
        'store' => 'laboratoire.permission:equipements.create',
        'edit' => 'laboratoire.permission:equipements.edit',
        'update' => 'laboratoire.permission:equipements.edit',
        'destroy' => 'laboratoire.permission:equipements.delete',
        'show' => 'laboratoire.permission:equipements.view',
    ]);
    Route::get('equipements/{id}/reserver', [EquipementsController::class, 'showReservationForm'])
        ->name('equipements.reserver')->middleware('laboratoire.permission:equipements.reserve');
    Route::post('equipements/{id}/reserver', [EquipementsController::class, 'storeReservation'])
        ->name('equipements.reservation.store')->middleware('laboratoire.permission:equipements.reserve');

    // Utilisateurs externes
    Route::resource('externes', UserExterneController::class)->middleware('laboratoire.permission:membres.view');

    // Rôles de laboratoire (réservé admin)
    Route::resource('roles', RoleLaboController::class);

    // Gestion des candidatures (admin/secretaire)
    Route::prefix('candidatures')->name('candidatures.')->middleware('laboratoire.permission:candidatures.view')->group(function () {
        Route::get('/', [CandidatureController::class, 'index'])->name('index');
        Route::get('/{id}', [CandidatureController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [CandidatureController::class, 'approve'])->name('approve')->middleware('laboratoire.permission:candidatures.process');
        Route::post('/{id}/reject', [CandidatureController::class, 'reject'])->name('reject')->middleware('laboratoire.permission:candidatures.process');
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
    })->name('dashboard')->middleware('laboratoire.permission:dashboard.view');

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

Route::get('/presentation_ufd_tsi', [LaboratoireController::class, 'index']);
//Route::get('/presentation_labo/{id}',  [LaboratoireController::class, 'show']);

// Dashboard admin du laboratoire
Route::get('/laboratoires/{code_lab}/admin', [AdminLaboratoireController::class, 'dashboard'])
    ->name('laboratoires.admin.dashboard')
    ->middleware('laboratoire.permission:dashboard.view');

// Nouveau Dashboard admin du laboratoire
Route::get('/laboratoires/{code_lab}/admin/new', [AdminLaboratoireController::class, 'dashboardNew'])
    ->name('laboratoires.admin.dashboard.new')
    ->middleware('laboratoire.permission:dashboard.view');

// Gestion des membres du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/membres', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'membres'])
    ->name('laboratoires.admin.membres')
    ->middleware('laboratoire.permission:membres.view');

// Ajout d'un membre (admin labo)
Route::get('/laboratoires/{code_lab}/admin/membres/ajouter', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'ajouterMembreForm'])
    ->name('laboratoires.admin.membres.create')
    ->middleware('laboratoire.permission:membres.create');
Route::post('/laboratoires/{code_lab}/admin/membres/ajouter', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'ajouterMembre'])
    ->name('laboratoires.admin.membres.store')
    ->middleware('laboratoire.permission:membres.create');

// Voir la fiche d'un membre
Route::get('/laboratoires/{code_lab}/admin/membres/{membre}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'ficheMembre'])
    ->name('laboratoires.admin.membres.show')
    ->middleware('laboratoire.permission:membres.view');
// Modifier un membre
Route::get('/laboratoires/{code_lab}/admin/membres/{membre}/modifier', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'modifierMembreForm'])
    ->name('laboratoires.admin.membres.edit')
    ->middleware('laboratoire.permission:membres.edit');
Route::post('/laboratoires/{code_lab}/admin/membres/{membre}/modifier', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'modifierMembre'])
    ->name('laboratoires.admin.membres.update')
    ->middleware('laboratoire.permission:membres.edit');
// Supprimer un membre
Route::post('/laboratoires/{code_lab}/admin/membres/{membre}/supprimer', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'supprimerMembre'])
    ->name('laboratoires.admin.membres.destroy')
    ->middleware('laboratoire.permission:membres.delete');

// Actions groupées sur les membres (admin labo)
Route::post('/laboratoires/{code_lab}/admin/membres/actions-groupees', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'actionsGroupeesMembres'])
    ->name('laboratoires.admin.membres.bulk')
    ->middleware('laboratoire.permission:membres.edit');

// Gestion des candidatures du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/candidatures', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'candidatures'])
    ->name('laboratoires.admin.candidatures')
    ->middleware('laboratoire.permission:candidatures.view');
Route::get('/laboratoires/{code_lab}/admin/candidatures/{candidature}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'candidatureShow'])
    ->name('laboratoires.admin.candidatures.show')
    ->middleware('laboratoire.permission:candidatures.view');
Route::post('/laboratoires/{code_lab}/admin/candidatures/{candidature}/approve', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'candidatureApprove'])
    ->name('laboratoires.admin.candidatures.approve')
    ->middleware('laboratoire.permission:candidatures.process');
Route::post('/laboratoires/{code_lab}/admin/candidatures/{candidature}/reject', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'candidatureReject'])
    ->name('laboratoires.admin.candidatures.reject')
    ->middleware('laboratoire.permission:candidatures.process');


// Gestion des utilisateurs externes du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/externes', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externes'])
    ->name('laboratoires.admin.externes')
    ->middleware('laboratoire.permission:membres.view');

Route::get('/laboratoires/{code_lab}/admin/externes/create', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeCreate'])
    ->name('laboratoires.admin.externes.create')
    ->middleware('laboratoire.permission:membres.create');

Route::post('/laboratoires/{code_lab}/admin/externes', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeStore'])
    ->name('laboratoires.admin.externes.store')
    ->middleware('laboratoire.permission:membres.create');

Route::get('/laboratoires/{code_lab}/admin/externes/{externe}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeShow'])
    ->name('laboratoires.admin.externes.show')
    ->middleware('laboratoire.permission:membres.view');

Route::get('/laboratoires/{code_lab}/admin/externes/{externe}/edit', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeEdit'])
    ->name('laboratoires.admin.externes.edit')
    ->middleware('laboratoire.permission:membres.edit');

Route::match(['put', 'post'], '/laboratoires/{code_lab}/admin/externes/{externe}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeUpdate'])
    ->name('laboratoires.admin.externes.update')
    ->middleware('laboratoire.permission:membres.edit');

Route::post('/laboratoires/{code_lab}/admin/externes/{externe}/delete', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeDestroy'])
    ->where('externe', '[A-Za-z0-9\-]+')
    ->name('laboratoires.admin.externes.destroy')
    ->middleware('laboratoire.permission:membres.delete');

Route::post('/laboratoires/{code_lab}/admin/externes/{externe}/reset-password', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeResetPassword'])
    ->where('externe', '[A-Za-z0-9\-]+')
    ->name('laboratoires.admin.externes.reset-password')
    ->middleware('laboratoire.permission:membres.edit');


// Gestion des projets du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/projets', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projets'])
    ->name('laboratoires.admin.projets')
    ->middleware('laboratoire.permission:projets.view');
Route::get('/laboratoires/{code_lab}/admin/projets/create', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetCreate'])
    ->name('laboratoires.admin.projets.create')
    ->middleware('laboratoire.permission:projets.create');
Route::post('/laboratoires/{code_lab}/admin/projets', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetStore'])
    ->name('laboratoires.admin.projets.store')
    ->middleware('laboratoire.permission:projets.create');
Route::get('/laboratoires/{code_lab}/admin/projets/{projet}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetShow'])
    ->name('laboratoires.admin.projets.show')
    ->middleware('laboratoire.permission:projets.view');
Route::get('/laboratoires/{code_lab}/admin/projets/{projet}/edit', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetEdit'])
    ->name('laboratoires.admin.projets.edit')
    ->middleware('laboratoire.permission:projets.edit');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetUpdate'])
    ->name('laboratoires.admin.projets.update')
    ->middleware('laboratoire.permission:projets.edit');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/delete', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDestroy'])
    ->name('laboratoires.admin.projets.destroy')
    ->middleware('laboratoire.permission:projets.delete');

// Gestion des participants aux projets
Route::get('/laboratoires/{code_lab}/admin/projets/{projet}/participants', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetParticipants'])
    ->name('laboratoires.admin.projets.participants')
    ->middleware('laboratoire.permission:projets.participants');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/participants', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetParticipantsStore'])
    ->name('laboratoires.admin.projets.participants.store')
    ->middleware('laboratoire.permission:projets.participants');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/participants/{participant}/delete', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetParticipantsDestroy'])
    ->name('laboratoires.admin.projets.participants.destroy')
    ->middleware('laboratoire.permission:projets.participants');

// Gestion des documents des projets
Route::get('/laboratoires/{code_lab}/admin/projets/{projet}/documents', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDocuments'])
    ->name('laboratoires.admin.projets.documents')
    ->middleware('laboratoire.permission:projets.documents');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/documents', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDocumentsStore'])
    ->name('laboratoires.admin.projets.documents.store')
    ->middleware('laboratoire.permission:projets.documents');
Route::delete('/laboratoires/{code_lab}/admin/projets/{projet}/documents/{document}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDocumentsDestroy'])
    ->name('laboratoires.admin.projets.documents.destroy');
Route::put('/laboratoires/{code_lab}/admin/projets/{projet}/documents/{document}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDocumentsUpdate'])
    ->name('laboratoires.admin.projets.documents.update');

// Gestion des équipements du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/equipements', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipements'])
    ->name('laboratoires.admin.equipements')
    ->middleware('laboratoire.permission:equipements.view');
Route::get('/laboratoires/{code_lab}/admin/equipements/create', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementCreate'])
    ->name('laboratoires.admin.equipements.create')
    ->middleware('laboratoire.permission:equipements.create');
Route::post('/laboratoires/{code_lab}/admin/equipements', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementStore'])
    ->name('laboratoires.admin.equipements.store')
    ->middleware('laboratoire.permission:equipements.create');
Route::get('/laboratoires/{code_lab}/admin/equipements/{equipement}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementShow'])
    ->name('laboratoires.admin.equipements.show')
    ->middleware('laboratoire.permission:equipements.view');
Route::get('/laboratoires/{code_lab}/admin/equipements/{equipement}/edit', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementEdit'])
    ->name('laboratoires.admin.equipements.edit')
    ->middleware('laboratoire.permission:equipements.edit');
Route::put('/laboratoires/{code_lab}/admin/equipements/{equipement}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementUpdate'])
    ->name('laboratoires.admin.equipements.update')
    ->middleware('laboratoire.permission:equipements.edit');
Route::delete('/laboratoires/{code_lab}/admin/equipements/{equipement}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementDestroy'])
    ->name('laboratoires.admin.equipements.destroy')
    ->middleware('laboratoire.permission:equipements.delete');

// Gestion des entretiens des équipements
Route::get('/laboratoires/{code_lab}/admin/equipements/{equipement}/entretiens', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementEntretiens'])
    ->name('laboratoires.admin.equipements.entretiens')
    ->middleware('laboratoire.permission:equipements.entretenir');
Route::post('/laboratoires/{code_lab}/admin/equipements/{equipement}/entretiens', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementEntretienStore'])
    ->name('laboratoires.admin.equipements.entretien.store')
    ->middleware('laboratoire.permission:equipements.entretenir');
Route::put('/laboratoires/{code_lab}/admin/equipements/{equipement}/entretiens/{entretien}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementEntretienUpdate'])
    ->name('laboratoires.admin.equipements.entretien.update')
    ->middleware('laboratoire.permission:equipements.entretenir');

// Gestion des réservations des équipements
Route::get('/laboratoires/{code_lab}/admin/equipements/{equipement}/reservations', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementReservations'])
    ->name('laboratoires.admin.equipements.reservations')
    ->middleware('laboratoire.permission:equipements.reserve');
Route::post('/laboratoires/{code_lab}/admin/equipements/{equipement}/reservations', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementReservationStore'])
    ->name('laboratoires.admin.equipements.reservation.store')
    ->middleware('laboratoire.permission:equipements.reserve');
Route::put('/laboratoires/{code_lab}/admin/equipements/{equipement}/reservations/{reservation}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementReservationUpdate'])
    ->name('laboratoires.admin.equipements.reservation.update')
    ->middleware('laboratoire.permission:equipements.reserve');

// Routes pour le reporting et les statistiques
Route::get('/laboratoires/{code_lab}/admin/reporting', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'reporting'])->name('laboratoires.admin.reporting');
Route::get('/laboratoires/{code_lab}/admin/stats/equipements', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementsStats'])->name('laboratoires.admin.stats.equipements');
Route::get('/laboratoires/{code_lab}/admin/reports/pdf', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'generateReportPDF'])->name('laboratoires.admin.reports.pdf');
Route::get('/laboratoires/{code_lab}/admin/reports/excel', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'generateReportExcel'])->name('laboratoires.admin.reports.excel');

// Routes pour les rapports personnalisés
Route::get('/laboratoires/{code_lab}/admin/rapports', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'rapports'])->name('laboratoires.admin.rapports');
Route::get('/laboratoires/{code_lab}/admin/rapports/create', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'rapportCreate'])->name('laboratoires.admin.rapports.create');
Route::post('/laboratoires/{code_lab}/admin/rapports', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'rapportStore'])->name('laboratoires.admin.rapports.store');
Route::get('/laboratoires/{code_lab}/admin/rapports/{rapport}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'rapportShow'])->name('laboratoires.admin.rapports.show');
Route::get('/laboratoires/{code_lab}/admin/rapports/{rapport}/download', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'rapportDownload'])->name('laboratoires.admin.rapports.download');
Route::delete('/laboratoires/{code_lab}/admin/rapports/{rapport}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'rapportDestroy'])->name('laboratoires.admin.rapports.destroy');
Route::get('/laboratoires/{code_lab}/admin/rapports/{rapport}/view', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'rapportView'])->name('laboratoires.admin.rapports.view');

// Routes pour les notifications et alertes
Route::get('/laboratoires/{code_lab}/admin/notifications', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'notifications'])->name('laboratoires.admin.notifications');
Route::post('/laboratoires/{code_lab}/admin/notifications/{notification_id}/mark-read', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'notificationMarkAsRead'])->name('laboratoires.admin.notifications.mark-read');
Route::post('/laboratoires/{code_lab}/admin/notifications/mark-all-read', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'notificationsMarkAllAsRead'])->name('laboratoires.admin.notifications.mark-all-read');
Route::delete('/laboratoires/{code_lab}/admin/notifications/{notification_id}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'notificationDestroy'])->name('laboratoires.admin.notifications.destroy');

// Routes pour les alertes
Route::get('/laboratoires/{code_lab}/admin/alertes', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'alertes'])->name('laboratoires.admin.alertes');
Route::post('/laboratoires/{code_lab}/admin/alertes/check', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'runAlertChecks'])->name('laboratoires.admin.alertes.check');

// Routes AJAX pour les notifications
Route::get('/laboratoires/{code_lab}/admin/notifications/unread', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'getUnreadNotifications'])->name('laboratoires.admin.notifications.unread');
Route::get('/laboratoires/{code_lab}/admin/notifications/unread-count', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'getUnreadNotificationsCount'])->name('laboratoires.admin.notifications.unread-count');

// Routes pour les invitations de laboratoire
Route::get('/laboratoires/{code_lab}/admin/invitations', [\App\Http\Controllers\labo\InvitationController::class, 'index'])->name('laboratoires.admin.invitations');
Route::post('/laboratoires/{code_lab}/admin/invitations', [\App\Http\Controllers\labo\InvitationController::class, 'store'])->name('laboratoires.admin.invitations.store');
Route::delete('/laboratoires/{code_lab}/admin/invitations/{invitation}', [\App\Http\Controllers\labo\InvitationController::class, 'destroy'])->name('laboratoires.admin.invitations.destroy');

// Routes publiques pour les invitations
Route::get('/invitation/{token}', [\App\Http\Controllers\labo\InvitationController::class, 'accepterInvitation'])->name('laboratoires.invitation.accepter');
Route::post('/invitation/{token}', [\App\Http\Controllers\labo\InvitationController::class, 'traiterInvitation'])->name('laboratoires.invitation.traiter');

// Route courte pour les QR codes (plus fiable)
Route::get('/i/{token}', [\App\Http\Controllers\labo\InvitationController::class, 'accepterInvitation'])->name('laboratoires.invitation.short');

// Route pour télécharger le QR code
Route::get('/invitation-qr/{token}', [\App\Http\Controllers\labo\InvitationController::class, 'telechargerQRCode'])->name('laboratoires.invitation.qr');

// Annonces labo (notifications globales)
Route::get('/laboratoires/{code_lab}/admin/annonces', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'annonces'])->name('laboratoires.admin.annonces');
Route::post('/laboratoires/{code_lab}/admin/annonces', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'storeAnnonce'])->name('laboratoires.admin.annonces.store');
Route::delete('/laboratoires/{code_lab}/admin/annonces/{id}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'deleteAnnonce'])->name('laboratoires.admin.annonces.delete');
Route::get('/laboratoires/{code_lab}/admin/annonces/{id}/download', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'downloadAnnonceFile'])->name('laboratoires.admin.annonces.download');

// Publications du laboratoire (admin)
Route::prefix('/laboratoires/{code_lab}/admin/publications')->name('laboratoires.admin.publications.')->middleware('laboratoire.permission:publications.view')->group(function () {
    Route::get('/', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'publications'])
        ->name('index');
    Route::get('/create', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'publicationCreate'])
        ->name('create')->middleware('laboratoire.permission:publications.create');
    Route::post('/', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'publicationStore'])
        ->name('store')->middleware('laboratoire.permission:publications.create');
    Route::get('/{publication}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'publicationShow'])
        ->name('show');
    Route::get('/{publication}/edit', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'publicationEdit'])
        ->name('edit')->middleware('laboratoire.permission:publications.edit');
    Route::put('/{publication}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'publicationUpdate'])
        ->name('update')->middleware('laboratoire.permission:publications.edit');
    Route::delete('/{publication}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'publicationDestroy'])
        ->name('destroy')->middleware('laboratoire.permission:publications.delete');
});
