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

// Dashboard admin du laboratoire
Route::get('/laboratoires/{code_lab}/admin', [AdminLaboratoireController::class, 'dashboard'])->name('laboratoires.admin.dashboard');

// Nouveau Dashboard admin du laboratoire
Route::get('/laboratoires/{code_lab}/admin/new', [AdminLaboratoireController::class, 'dashboardNew'])->name('laboratoires.admin.dashboard.new');

// Gestion des membres du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/membres', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'membres'])->name('laboratoires.admin.membres');

// Ajout d'un membre (admin labo)
Route::get('/laboratoires/{code_lab}/admin/membres/ajouter', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'ajouterMembreForm'])->name('laboratoires.admin.membres.create');
Route::post('/laboratoires/{code_lab}/admin/membres/ajouter', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'ajouterMembre'])->name('laboratoires.admin.membres.store');

// Voir la fiche d'un membre
Route::get('/laboratoires/{code_lab}/admin/membres/{membre}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'ficheMembre'])->name('laboratoires.admin.membres.show');
// Modifier un membre
Route::get('/laboratoires/{code_lab}/admin/membres/{membre}/modifier', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'modifierMembreForm'])->name('laboratoires.admin.membres.edit');
Route::post('/laboratoires/{code_lab}/admin/membres/{membre}/modifier', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'modifierMembre'])->name('laboratoires.admin.membres.update');
// Supprimer un membre
Route::post('/laboratoires/{code_lab}/admin/membres/{membre}/supprimer', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'supprimerMembre'])->name('laboratoires.admin.membres.destroy');

// Actions groupées sur les membres (admin labo)
Route::post('/laboratoires/{code_lab}/admin/membres/actions-groupees', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'actionsGroupeesMembres'])->name('laboratoires.admin.membres.bulk');

// Gestion des candidatures du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/candidatures', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'candidatures'])->name('laboratoires.admin.candidatures');
Route::get('/laboratoires/{code_lab}/admin/candidatures/{candidature}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'candidatureShow'])->name('laboratoires.admin.candidatures.show');
Route::post('/laboratoires/{code_lab}/admin/candidatures/{candidature}/approve', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'candidatureApprove'])->name('laboratoires.admin.candidatures.approve');
Route::post('/laboratoires/{code_lab}/admin/candidatures/{candidature}/reject', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'candidatureReject'])->name('laboratoires.admin.candidatures.reject');

// Gestion des utilisateurs externes du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/externes', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externes'])->name('laboratoires.admin.externes');
Route::get('/laboratoires/{code_lab}/admin/externes/create', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeCreate'])->name('laboratoires.admin.externes.create');
Route::post('/laboratoires/{code_lab}/admin/externes', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeStore'])->name('laboratoires.admin.externes.store');
Route::get('/laboratoires/{code_lab}/admin/externes/{externe}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeShow'])->name('laboratoires.admin.externes.show');
Route::get('/laboratoires/{code_lab}/admin/externes/{externe}/edit', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeEdit'])->name('laboratoires.admin.externes.edit');
Route::match(['put', 'post'], '/laboratoires/{code_lab}/admin/externes/{externe}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeUpdate'])->name('laboratoires.admin.externes.update');
Route::post('/laboratoires/{code_lab}/admin/externes/{externe}/delete', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeDestroy'])
    ->where('externe', '[A-Za-z0-9\-]+')
    ->name('laboratoires.admin.externes.destroy');
Route::post('/laboratoires/{code_lab}/admin/externes/{externe}/reset-password', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'externeResetPassword'])
    ->where('externe', '[A-Za-z0-9\-]+')
    ->name('laboratoires.admin.externes.reset-password');

// Gestion des projets du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/projets', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projets'])->name('laboratoires.admin.projets');
Route::get('/laboratoires/{code_lab}/admin/projets/create', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetCreate'])->name('laboratoires.admin.projets.create');
Route::post('/laboratoires/{code_lab}/admin/projets', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetStore'])->name('laboratoires.admin.projets.store');
Route::get('/laboratoires/{code_lab}/admin/projets/{projet}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetShow'])->name('laboratoires.admin.projets.show');
Route::get('/laboratoires/{code_lab}/admin/projets/{projet}/edit', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetEdit'])->name('laboratoires.admin.projets.edit');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetUpdate'])->name('laboratoires.admin.projets.update');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/delete', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDestroy'])->name('laboratoires.admin.projets.destroy');

// Gestion des participants aux projets
Route::get('/laboratoires/{code_lab}/admin/projets/{projet}/participants', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetParticipants'])->name('laboratoires.admin.projets.participants');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/participants', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetParticipantsStore'])->name('laboratoires.admin.projets.participants.store');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/participants/{participant}/delete', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetParticipantsDestroy'])->name('laboratoires.admin.projets.participants.destroy');

// Gestion des documents des projets
Route::get('/laboratoires/{code_lab}/admin/projets/{projet}/documents', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDocuments'])->name('laboratoires.admin.projets.documents');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/documents', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDocumentsStore'])->name('laboratoires.admin.projets.documents.store');
Route::post('/laboratoires/{code_lab}/admin/projets/{projet}/documents/{document}/delete', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'projetDocumentsDestroy'])->name('laboratoires.admin.projets.documents.destroy');

// Gestion des équipements du laboratoire (admin)
Route::get('/laboratoires/{code_lab}/admin/equipements', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipements'])->name('laboratoires.admin.equipements');
Route::get('/laboratoires/{code_lab}/admin/equipements/create', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementCreate'])->name('laboratoires.admin.equipements.create');
Route::post('/laboratoires/{code_lab}/admin/equipements', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementStore'])->name('laboratoires.admin.equipements.store');
Route::get('/laboratoires/{code_lab}/admin/equipements/{equipement}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementShow'])->name('laboratoires.admin.equipements.show');
Route::get('/laboratoires/{code_lab}/admin/equipements/{equipement}/edit', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementEdit'])->name('laboratoires.admin.equipements.edit');
Route::put('/laboratoires/{code_lab}/admin/equipements/{equipement}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementUpdate'])->name('laboratoires.admin.equipements.update');
Route::delete('/laboratoires/{code_lab}/admin/equipements/{equipement}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementDestroy'])->name('laboratoires.admin.equipements.destroy');

// Gestion des entretiens des équipements
Route::get('/laboratoires/{code_lab}/admin/equipements/{equipement}/entretiens', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementEntretiens'])->name('laboratoires.admin.equipements.entretiens');
Route::post('/laboratoires/{code_lab}/admin/equipements/{equipement}/entretiens', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementEntretienStore'])->name('laboratoires.admin.equipements.entretien.store');
Route::put('/laboratoires/{code_lab}/admin/equipements/{equipement}/entretiens/{entretien}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementEntretienUpdate'])->name('laboratoires.admin.equipements.entretien.update');

// Gestion des réservations des équipements
Route::get('/laboratoires/{code_lab}/admin/equipements/{equipement}/reservations', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementReservations'])->name('laboratoires.admin.equipements.reservations');
Route::post('/laboratoires/{code_lab}/admin/equipements/{equipement}/reservations', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementReservationStore'])->name('laboratoires.admin.equipements.reservation.store');
Route::put('/laboratoires/{code_lab}/admin/equipements/{equipement}/reservations/{reservation}', [\App\Http\Controllers\Labo\AdminLaboratoireController::class, 'equipementReservationUpdate'])->name('laboratoires.admin.equipements.reservation.update');

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
