<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\BidangController;
use App\Http\Controllers\Api\VendorController;
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
 
// Authenticated User Route
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'user' => $request->user()
    ]);
});

// Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
