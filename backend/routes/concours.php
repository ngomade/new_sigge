
<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\concours\EcoleControllerApi;

use App\Http\Controllers\concours\SiteCompositionControllerApi;
use App\Http\Controllers\concours\DossierControllerApi;
use App\Http\Controllers\concours\CentreExamenControllerApi;
use App\Http\Controllers\concours\CentreDepotControllerApi;
use App\Http\Controllers\concours\CandidatEcoleControllerApi;
use App\Http\Controllers\concours\CompositionControllerApi;
use App\Http\Controllers\concours\EcoleElementControllerApi;

Route::apiResource("ecole", EcoleControllerApi::class);
Route::apiResource("centre_depot", CentreDepotControllerApi::class);
Route::apiResource("centre_examen", CentreExamenControllerApi::class);
Route::apiResource("dossier", DossierControllerApi::class);
Route::apiResource("site_composition", SiteCompositionControllerApi::class);
Route::apiResource("candidat_ecole", CandidatEcoleControllerApi::class);
Route::apiResource("composition", CompositionControllerApi::class);
Route::apiResource("ecole_element", EcoleElementControllerApi::class);
