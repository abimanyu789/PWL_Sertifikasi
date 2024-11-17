<?php

use App\Http\Controllers\BidangController;
use App\Http\Controllers\JenisSertifikasiController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LevelPelatihanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\SertifikasiController;
use App\Http\Controllers\WelcomeController;

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

// Perbaiki penutupan kurung kurawal di sini
Route::pattern('id', '[0-9]+');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index']); //halaman awal
    
    Route::group(['middleware' => 'authorize:ADM'], function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    }); 

    // Route::group(['prefix' => 'user'], function () {
    Route::group(['prefix' => 'user', 'middleware'=> 'authorize:ADM'], function(){
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

    Route::group(['prefix' => 'level', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [LevelController::class, 'index']);
        Route::post('list', [LevelController::class, 'list']);
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
    });
    Route::group(['prefix' => 'bidang', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [BidangController::class, 'index']);      
        Route::post('/list', [BidangController::class, 'list']);   
    });
    // Route::group(['prefix' => 'jenis_sertifikasi'], function () {
    Route::group(['prefix' => 'jenis_sertifikasi', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [JenisSertifikasiController::class, 'index']);          
        Route::post('/list', [JenisSertifikasiController::class, 'list']);      
    });

    // Route::group(['prefix' => 'level_pelatihan'], function () {
    Route::group(['prefix' => 'level_pelatihan', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [LevelPelatihanController::class, 'index']);          
        Route::post('/list', [LevelPelatihanController::class, 'list']);      
    });

    Route::group(['prefix' => 'pelatihan', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [PelatihanController::class, 'index']);
        Route::post('/list', [PelatihanController::class, 'list']);
        Route::get('/create_ajax', [PelatihanController::class, 'create_ajax']);
        Route::post('/ajax', [PelatihanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [PelatihanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [PelatihanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [PelatihanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [PelatihanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [PelatihanController::class, 'delete_ajax']);
    });

    Route::group(['prefix' => 'level_pelatihan', 'middleware'=> 'authorize:ADM'], function(){

    });

    Route::group(['prefix' => 'sertifikasi', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [SertifikasiController::class, 'index']);
        Route::post('/list', [SertifikasiController::class, 'list']);
        Route::get('/create_ajax', [SertifikasiController::class, 'create_ajax']);
        Route::post('/ajax', [SertifikasiController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [SertifikasiController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [SertifikasiController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [SertifikasiController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax',[SertifikasiController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [SertifikasiController::class, 'delete_ajax']);
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

});
