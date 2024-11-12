<?php

use App\Http\Controllers\BidangController;
use App\Http\Controllers\JenisSertifikasiController;
use App\Http\Controllers\LevelPelatihanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'bidang'], function () {
    Route::get('/', [BidangController::class, 'index']);          // halaman awal bidang
    Route::post('/list', [BidangController::class, 'list']);      // data bidang dalam bentuk json untuk datatables
});

Route::group(['prefix' => 'jenis_sertifikasi'], function () {
    Route::get('/', [JenisSertifikasiController::class, 'index']);          // halaman awal bidang
    Route::post('/list', [JenisSertifikasiController::class, 'list']);      // data bidang dalam bentuk json untuk datatables
});

Route::group(['prefix' => 'level_pelatihan'], function () {
    Route::get('/', [LevelPelatihanController::class, 'index']);          // halaman awal bidang
    Route::post('/list', [LevelPelatihanController::class, 'list']);      // data bidang dalam bentuk json untuk datatables
});
