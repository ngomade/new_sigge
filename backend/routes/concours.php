<?php

use App\Http\Controllers\concours\auth\AuthController;
use App\Http\Controllers\concours\auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("check-token", [AuthController::class, 'checkToken']);

//Route d'authentification
Route::middleware(["guest.sanctum", "throttle:5,1"])->group(function () {
    Route::post("login", [AuthController::class, 'login']);
    Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
});
Route::middleware("auth:sanctum")->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });
    Route::get("logout", [AuthController::class, 'logout']);
    Route::get("refresh-token", [AuthController::class, 'refresh']);
});

//Route principale

