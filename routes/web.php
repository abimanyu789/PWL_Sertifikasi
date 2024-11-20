<?php

use App\Http\Controllers\BidangController;
use App\Http\Controllers\JenisSertifikasiController;
use App\Http\Controllers\LevelPelatihanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::pattern('id', '[0-9]+');

Route::group(['prefix' => 'bidang'], function () {
    Route::get('/', [BidangController::class, 'index']);
    Route::post('/list', [BidangController::class, 'list']);
    Route::get('/create_ajax', [BidangController::class, 'create_ajax']);
    Route::get('/{id}/edit_ajax', [BidangController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [BidangController::class, 'update_ajax']);
    Route::post('/ajax', [BidangController::class, 'store_ajax']);
    Route::get('/{id}/delete_ajax', [BidangController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [BidangController::class, 'delete_ajax']);
    Route::get('/{id}/show_ajax', [BidangController::class, 'show_ajax']);
    Route::get('/import', [BidangController::class, 'import']);
        Route::post('/import_ajax', [BidangController::class, 'import_ajax']);
    Route::get('/export_excel', [BidangController::class, 'export_excel']);
    Route::get('/export_pdf', [BidangController::class, 'export_pdf']);
});

Route::group(['prefix' => 'jenis_sertifikasi'], function () {
    Route::get('/', [JenisSertifikasiController::class, 'index']);
    Route::post('/list', [JenisSertifikasiController::class, 'list']);
    Route::get('/{id}/show_ajax', [JenisSertifikasiController::class, 'show_ajax']);
    Route::get('/export_excel', [JenisSertifikasiController::class, 'export_excel']);
    Route::get('/export_pdf', [JenisSertifikasiController::class, 'export_pdf']);
});

Route::group(['prefix' => 'level_pelatihan'], function () {
    Route::get('/', [LevelPelatihanController::class, 'index']);
    Route::post('/list', [LevelPelatihanController::class, 'list']);
    Route::get('/{id}/show_ajax', [LevelPelatihanController::class, 'show_ajax']);
    Route::get('/export_excel', [LevelPelatihanController::class, 'export_excel']);
    Route::get('/export_pdf', [LevelPelatihanController::class, 'export_pdf']);
});
