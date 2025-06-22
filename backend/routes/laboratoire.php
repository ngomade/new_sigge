<?php


use App\Http\Controllers\labo\LaboratoireController;

Route::get('/presentation_ufd_tsi',  [LaboratoireController::class, 'index']);
Route::get('/presentation_labo/{id}',  [LaboratoireController::class, 'show']);
