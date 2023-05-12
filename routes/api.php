<?php

use App\Http\Controllers\SignInController;
use App\Http\Controllers\SignOutController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', SignInController::class);
Route::middleware('auth:sanctum')->post('/logout', SignOutController::class);
