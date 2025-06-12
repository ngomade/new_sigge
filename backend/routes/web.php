<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EcController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view("sige_app.frontend.index");})->name("home");
Route::post("/login", [AuthController::class ,'store'])->name("login");
Route::get("/logout", [AuthController::class ,'index'])->name("logout");

Route::get("/maintenance", [EcController::class ,'maintenance'])->name("maintenance");
