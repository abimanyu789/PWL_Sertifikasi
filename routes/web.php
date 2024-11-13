<?php

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

Route::get('/', function () {
    return view('welcome');
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
