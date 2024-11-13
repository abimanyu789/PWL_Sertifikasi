<?php

use App\Http\Controllers\BidangController;
use App\Http\Controllers\JenisSertifikasiController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LevelPelatihanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AuthController;

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

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);          
    Route::post('list', [UserController::class, 'list']);
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);    
    Route::post('/ajax', [UserController::class, 'store_ajax']);
    Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);    
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); 
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);   
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); 
});

Route::get('/level', [LevelController::class, 'index']);
Route::post('/level/list', [LevelController::class, 'list']);
Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']);
Route::post('/level/ajax', [LevelController::class, 'store_ajax']);
Route::get('/level/{id}/show_ajax', [LevelController::class, 'show_ajax']);
Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']);
Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);

Route::get('/bidang', [BidangController::class, 'index']);      
Route::post('/bidang/list', [BidangController::class, 'list']);   


Route::group(['prefix' => 'jenis_sertifikasi'], function () {
    Route::get('/', [JenisSertifikasiController::class, 'index']);          
    Route::post('/list', [JenisSertifikasiController::class, 'list']);      
});
Route::group(['prefix' => 'level_pelatihan'], function () {
    Route::get('/', [LevelPelatihanController::class, 'index']);          
    Route::post('/list', [LevelPelatihanController::class, 'list']);      
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/vendors', [VendorController::class, 'index'])->name('vendor.index');
Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendor.create');
Route::post('/vendors', [VendorController::class, 'store'])->name('vendor.store');
Route::get('/vendors/{id}', [VendorController::class, 'show'])->name('vendor.show');
Route::get('/vendors/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
Route::put('/vendors/{id}', [VendorController::class, 'update'])->name('vendor.update');
Route::delete('/vendors/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');
