<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SertifikasiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BidangController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\StatistikController;
use App\Http\Controllers\Api\PelatihanController;
use App\Http\Controllers\Api\SertifikatController;

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

// Route::post('/login', [LoginController::class, 'login'])->name('login');

// Route::middleware('auth:api')->group(function () {
//     // User profile routes
//     Route::get('/user', [UserController::class, 'profile']);
//     Route::post('/user/update', [UserController::class, 'update']);
    
//     // Bidang routes
//     Route::get('/bidang', [BidangController::class, 'index']);
//     Route::post('/bidang', [BidangController::class, 'store']);

//     // Vendor routes
//     Route::get('/vendor', [VendorController::class, 'index']);
//     Route::post('/vendor', [VendorController::class, 'store']);

//     // Sertifikasi routes
//     Route::post('/uploads', [SertifikasiController::class, 'store']);

// Route::get('/statistics', [StatistikController::class, 'getStatistics']);
// });

Route::post('/login', LoginController::class)->name('login');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Bidang routes
Route::get('/bidang', [BidangController::class, 'index']);
Route::post('/bidang', [BidangController::class, 'store']);

// Vendor routes
Route::get('/vendor', [VendorController::class, 'index']);
Route::post('/vendor', [VendorController::class, 'store']);

// Sertifikasi routes
Route::post('/uploads', [SertifikasiController::class, 'store']);

Route::get('/statistics', [StatistikController::class, 'getStatistics']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [UserController::class, 'profile']);
});

Route::middleware('auth:api')->group(function () {
    // Update method menjadi PUT/POST
    Route::post('/user/update', [UserController::class, 'update']);
});

// Authenticated User Route
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'user' => $request->user()
    ]);
});

// Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::get("/sertifikasi", [SertifikasiController::class, "index"]);
Route::get("/sertifikasi/{id}", [SertifikasiController::class, "show"]);

Route::get("/pelatihan", [PelatihanController::class, "index"]);
Route::get("/pelatihan/{id}", [PelatihanController::class, "show"]);

Route::middleware('auth:api')->group(function () {
    Route::get("/sertifikat", [SertifikatController::class, "index"]);
    Route::get("/sertifikat/{id}", [SertifikatController::class, "show"]);
    Route::get('/jumlah-sertifikat', [SertifikatController::class, 'hitungSertifikat']);
});