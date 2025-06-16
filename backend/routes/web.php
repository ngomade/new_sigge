<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasculementController;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\concours\AdminConcoursController;
use App\Http\Controllers\EcController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view("sige_app.frontend.index");})->name("home");
Route::post("login", [AuthController::class ,'store'])->name("login");
Route::get("logout", [AuthController::class ,'index'])->name("logout");

Route::get("maintenance", [EcController::class ,'maintenance'])->name("maintenance");

// Routes pour la gestion de bureaux
Route::get("bureau/{type}", [BureauController::class ,'index'])->name("index_bureau");
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

require __DIR__."/requetes.php";
