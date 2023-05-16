<?php

use App\Http\Controllers\GetUserController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\SignOutController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\UpdatePatientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/signup', SignUpController::class);
Route::post('/login', SignInController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', GetUserController::class);
    Route::post('/logout', SignOutController::class);
    Route::put('/update', UpdatePatientController::class);
});

