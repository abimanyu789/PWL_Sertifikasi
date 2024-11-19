<?php

use App\Http\Controllers\BidangController;
use App\Http\Controllers\JenisSertifikasiController;
use App\Http\Controllers\LevelPelatihanController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('vendor')->group(function() {
    Route::get('/', [VendorController::class, 'index']);
    Route::post('/list', [VendorController::class, 'list']);
    // Letakkan route spesifik di sini
    Route::get('/import', [VendorController::class, 'import']);
    Route::post('/import_ajax', [VendorController::class, 'importAjax']);
    Route::get('/create_ajax', [VendorController::class, 'createAjax']);
    Route::get('/export_pdf', [VendorController::class, 'exportPdf']);
    Route::get('/confirm_ajax/{id}', [VendorController::class, 'confirmAjax']);
    Route::post('/store_ajax', [VendorController::class, 'storeAjax']);
    
    // Kemudian route dengan parameter
    Route::get('/{id}', [VendorController::class, 'show']);
    Route::get('/{id}/edit', [VendorController::class, 'edit']);
    Route::get('/{id}/edit_ajax', [VendorController::class, 'editAjax']);
    Route::get('/{id}/show_ajax', [VendorController::class, 'showAjax']);
    Route::put('/{id}', [VendorController::class, 'update']);
    Route::delete('/{id}', [VendorController::class, 'destroy']);
});