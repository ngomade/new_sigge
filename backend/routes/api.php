<?php

use Illuminate\Support\Facades\Route;


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



