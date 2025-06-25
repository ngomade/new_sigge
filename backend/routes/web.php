<?php

use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasculementController;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\concours\AdminConcoursController;
use App\Http\Controllers\EcController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\InscriptionAcademiqueController;
use App\Http\Controllers\labo\LaboratoireController;
use App\Http\Controllers\MairieController;
use App\Http\Controllers\OrganigrammeController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\share\DownloadController;
use App\Http\Controllers\UeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view("sige_app.frontend.index");})->name("home");
Route::post("login", [AuthController::class ,'store'])->name("login");
Route::get("logout", [AuthController::class ,'index'])->name("logout");


// Routes pour la gestion de bureaux
Route::get("bureau/{type}", [BureauController::class ,'index'])->name("index_bureau");
Route::get("bureau/{type}/affectation", [AffectationController::class ,'index'])->name("affectation_personnel");
Route::post("ajouter_bureau", [BureauController::class ,'store'])->name("ajouter_bureau");
Route::get("delete_bureau/{type_bureau}/{code_bureau}", [BureauController::class ,'destroy'])->name("delete_bureau");
Route::post("presentation_bureau", [BureauController::class ,'store_present'])->name("ajouter_presentation");
Route::get("presentation_departement/{code_bureau}", [BureauController::class ,'presentation_departement'])->name("presentation_departement");
Route::get("download_grille/{code_bureau}/{nom_document}", [BureauController::class ,'download_grille'])->name("download_grille");

// Routes pour la gestion des ressources
Route::get("gestion_role_perm", [RolePermissionController::class ,'index'])->name("gestion_role_perm");
Route::post("ajouter_role", [RolePermissionController::class ,'ajouter_role'])->name("ajouter_role");
Route::post("ajouter_perm", [RolePermissionController::class ,'ajouter_perm'])->name("ajouter_perm");

Route::get("delete_role/{id}", [RolePermissionController::class ,'destroy'])->name("delete_role");
Route::get("delete_perm/{id}", [RolePermissionController::class ,'delete_perm'])->name("delete_perm");
Route::get("assignation_index", [RolePermissionController::class ,'create'])->name("assignation_role_perm");
Route::post("add_role_pers", [RolePermissionController::class ,'add_role_pers'])->name("add_role_pers");
Route::post("add_perm_pers", [RolePermissionController::class ,'add_perm_pers'])->name("add_perm_pers");
// NOUVELLES ROUTES À AJOUTER
Route::get('/edit_role/{id}', [RolePermissionController::class, 'edit_role']);
Route::put('/update_role/{id}', [RolePermissionController::class, 'update_role']);
Route::get('/edit_perm/{id}', [RolePermissionController::class, 'edit_perm']);
Route::put('/update_perm/{id}', [RolePermissionController::class, 'update_perm']);
// Routes pour la gestion du personnel
Route::get("insription_personnel", [PersonnelController::class ,'index'])->name("insription_personnel");
Route::post("ajouter_personnel", [PersonnelController::class ,'store'])->name("ajouter_personnel");
Route::get("delete_personnel/{id}", [PersonnelController::class ,'destroy'])->name("delete_personnel");

// Routes pour la gestion des étudiants
Route::get("/liste_etudiant/{id}", [EtudiantController::class ,'show'])->name("liste_etudiant");
Route::post("/delete_user", [EtudiantController::class ,'delete_user'])->name("delete_user");
Route::post("/search_etudiant", [EtudiantController::class ,'search_etudiant'])->name("search_etudiant");
Route::post("/search_etudiant_site", [EtudiantController::class ,'search_etudiant_site'])->name("search_etudiant_site");
Route::post("/change_filiere", [EtudiantController::class ,'change_filiere'])->name("change_filiere");
Route::get("/update_info/{id}", [EtudiantController::class ,'update_info'])->name("update_info");
Route::post("/change_info_pers", [EtudiantController::class ,'change_info_pers'])->name("change_info_pers");
Route::post("/change_pwd", [EtudiantController::class ,'change_pwd'])->name("change_pwd");
Route::post("/changer_pwd_first", [EtudiantController::class ,'change_pwd_first'])->name("change_pwd_first");
Route::post("/change_photo", [EtudiantController::class ,'change_photo'])->name("change_photo");
Route::post("/change_info_sup", [EtudiantController::class ,'change_info_sup'])->name("change_info_sup");
Route::get("/update_info/{id}", [EtudiantController::class ,'update_info'])->name("update_info");
Route::get("/valider_paiement_index", [EtudiantController::class ,'valider_paiement_index'])->name("valider_paiement_index");
Route::get("/certificat_index", [EtudiantController::class ,'certificat_index'])->name("certificat_index");
Route::post("/certificat", [EtudiantController::class ,'certificat'])->name("certificat");
Route::get("/carte_index", [EtudiantController::class ,'carte_index'])->name("carte_index");
Route::post("/carte", [EtudiantController::class ,'carte'])->name("carte");
Route::get("/show_candidat_list", [EtudiantController::class ,'show_candidat_list'])->name("show_candidat_list");
Route::get("/show_candidat_list", [EtudiantController::class ,'show_candidat_list'])->name("show_candidat_list");
Route::post("/search_candidats", [EtudiantController::class ,'search_candidats'])->name("search_candidats");
Route::post("/find_candidats", [EtudiantController::class ,'find_candidats'])->name("find_candidats");
Route::get("/liste_site_formation", [EtudiantController::class ,'liste_site_formation'])->name("liste_site_formation");
Route::post("/changement_site_save", [EtudiantController::class ,'changement_site_save'])->name("changement_site_save");
Route::post("/find_candidats_site", [EtudiantController::class ,'find_candidats_site'])->name("find_candidats_site");

// Routes pour la gestion du basculement
Route::get('/basculement_index',  [BasculementController::class, 'index'])->name("basculement_index");
Route::post('/basculement_save',  [BasculementController::class, 'store']);
Route::post('/search_user/{view}',  [BasculementController::class, 'search_user']);

Route::get("/index_admin_concours", [AdminConcoursController::class ,'index'])->name("index_admin_concours");
Route::post("/search_candidat", [AdminConcoursController::class ,'search'])->name("search_candidat");
Route::post("/search_candidat_imp", [AdminConcoursController::class ,'search_imp'])->name("search_candidat_imp");
Route::get("/liste_candidat", [AdminConcoursController::class ,'create'])->name("liste_candidat");
Route::get("/ouvrir_fermer", [AdminConcoursController::class ,'show_session'])->name("ouvrir_fermer");
Route::post("/add_session", [AdminConcoursController::class ,'add_session'])->name("add_session");
Route::post("/delete_session", [AdminConcoursController::class ,'delete_session'])->name("delete_session");
Route::post("/update_session", [AdminConcoursController::class ,'update_session'])->name("update_session");
Route::post("/delete_cand", [AdminConcoursController::class ,'destroy'])->name("delete_cand");

Route::get("/download/{chemin}", [DownloadController::class ,'show'])->name("download");
//Route::post("/save_message", [MessageController::class ,'store'])->name("save_message");

//Route::get("/form_send_mail", [MailController::class ,'index'])->name("form_send_mail");
//Route::post("/send_mail_candidats", [MailController::class ,'store'])->name("send_mail_candidats");
//Route::post("/delete_mail", [MailController::class ,'supprimer'])->name("delete_mail");

Route::get("/inscription_administrative", [InscriptionAcademiqueController::class ,'index'])->name("inscription_academique");
Route::post("/recherche_candidat", [InscriptionAcademiqueController::class ,'recherche'])->name("recherche_candidat");
Route::post("/inscription_administrative", [InscriptionAcademiqueController::class ,'store'])->name("inscription_administrative");
Route::get("/telecharger_fiche/{code_ins}", [InscriptionAcademiqueController::class ,'production_fiche'])->name("telecharger_fiche");
Route::get("/telecharger_quitus/{code_quitus}", [InscriptionAcademiqueController::class ,'production_quitus'])->name("telecharger_quitus");
Route::get("/retelecharger_fiche/{code_user}", [InscriptionAcademiqueController::class ,'reproduction_document'])->name("retelecharger_fiche");
Route::get("/academique_index", [InscriptionAcademiqueController::class ,'inscription_academique_index'])->name("inscription_academique_index");
Route::get("/academique_download/{code_ins}", [InscriptionAcademiqueController::class ,'academie_download'])->name("academie_download");
Route::post("/academique_inscription", [InscriptionAcademiqueController::class ,'inscription_academique'])->name("inscription_academique");
Route::post("/recuperation_pwd", [InscriptionAcademiqueController::class ,'recuperation_pwd'])->name("recuperation_pwd");

Route::get("/gestion_semestre", [SemestreController::class ,'index'])->name("gestion_semestre");
Route::post("/ajouter_semestre", [SemestreController::class ,'store'])->name("ajouter_semestre");
Route::get("/delete_sem/{code_sem}", [SemestreController::class ,'destroy'])->name("delete_sem");

//Route::get("/gestion_grille", [GrilleController::class ,'index'])->name("gestion_grille");
//Route::post("/ajouter_grille", [GrilleController::class ,'store'])->name("ajouter_grille");
//Route::get("/delete_grille/{code_grille}", [GrilleController::class ,'destroy'])->name("delete_grille");


Route::get("/gestion_ue", [UeController::class ,'index'])->name("gestion_ue");
Route::post("/ajouter_ue", [UeController::class ,'store'])->name("ajouter_ue");
Route::get("/delete_ue/{code_ue}", [UeController::class ,'destroy'])->name("delete_ue");

Route::get("maintenance", [EcController::class ,'maintenance'])->name("maintenance");
Route::get("/gestion_ec", [EcController::class ,'index'])->name("gestion_ec");
Route::post("/ajouter_ec", [EcController::class ,'store'])->name("ajouter_ec");
Route::get("/delete_ec/{code_ec}", [EcController::class ,'destroy'])->name("delete_ec");
Route::get("/telecharger_cours_index", [EcController::class ,'show_download_ec'])->name("telecharger_cours_index");
Route::get("/download_ec/{code_ec}", [EcController::class ,'download_ec'])->name("download_ec");


Route::get("/index_actualite", [ActualiteController::class ,'index'])->name("index_actualite");
Route::post("/publier_actualite", [ActualiteController::class ,'store'])->name("publier_actualite");
Route::get("/details_actu/{code_actu}", [ActualiteController::class ,'show'])->name("details_actu");
Route::get("/all_actu", [ActualiteController::class ,'create'])->name("all_actu");
Route::get("/list_actu", [ActualiteController::class ,'list_actu'])->name("list_actu");
Route::get("/delete_actu/{id}", [ActualiteController::class ,'destroy'])->name("delete_actu");

Route::get("/organigramme", [OrganigrammeController::class ,'index'])->name("organigramme");
Route::get("/staff_admin", [OrganigrammeController::class ,'create'])->name("staff_admin");

Route::get("/pres_marie", [MairieController::class ,'index'])->name("pres_marie");
Route::get("/organigramme_mairie", [MairieController::class ,'create'])->name("organigramme_mairie");
Route::get("/projet_mairie", [MairieController::class ,'projet_mairie'])->name("projet_mairie");

require __DIR__."/requetes.php";
require __DIR__."/laboratoire.php";
