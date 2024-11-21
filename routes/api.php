<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SertifikasiController;

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

// Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::get("sertifikasi", [SertifikasiController::class, "index"]);
Route::get("sertifikasi/{id}", [SertifikasiController::class, "show"]);