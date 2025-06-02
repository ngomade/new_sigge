<?php

use Illuminate\Support\Facades\Route;


// Intégration des routes du concours avec un préfixe
Route::prefix('concours')->group(function () {
    require __DIR__.'/concours.php';
});
