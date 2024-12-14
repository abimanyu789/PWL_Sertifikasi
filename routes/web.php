<?php

use App\Http\Controllers\BidangController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\SertifikasiController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\DaftarDosenController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\UploadPelatihanController;
use App\Models\NotifikasiModel;
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

    Route::group(['middleware' => 'authorize:ADM,PMN,DSN'], function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });

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
        Route::get('/import', [UserController::class, 'import']);                      // ajax form upload excel
        Route::post('/import_ajax', [UserController::class, 'import_ajax']);           // ajax import excel
        Route::get('/export_excel', [UserController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);
        Route::get('/export_template', [UserController::class, 'exportTemplate']);
    });

    Route::group(['prefix' => 'level', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [LevelController::class, 'index']);
        Route::post('list', [LevelController::class, 'list']);
        Route::get('/import', [LevelController::class, 'import']);                      // ajax form upload excel
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);           // ajax import excel
        Route::get('/export_excel', [LevelController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'bidang', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [BidangController::class, 'index']);
        Route::post('/list', [BidangController::class, 'list']);
        Route::get('/create_ajax', [BidangController::class, 'create_ajax']);
        Route::post('/ajax', [BidangController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [BidangController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [BidangController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [BidangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BidangController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [BidangController::class, 'delete_ajax']);
        Route::get('/import', [BidangController::class, 'import']);                      // ajax form upload excel
        Route::post('/import_ajax', [BidangController::class, 'import_ajax']);           // ajax import excel
        Route::get('/export_excel', [BidangController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [BidangController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'periode', 'middleware'=> 'authorize:ADM,PMN,DSN'], function(){
        Route::get('/', [PeriodeController::class, 'index']);
        Route::post('/list', [PeriodeController::class, 'list']);
        Route::get('/create_ajax', [PeriodeController::class, 'create_ajax']);
        Route::post('/ajax', [PeriodeController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [PeriodeController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [PeriodeController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [PeriodeController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [PeriodeController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [PeriodeController::class, 'delete_ajax']);
        Route::get('/import', [PeriodeController::class, 'import']); // Form import
        Route::post('/import_ajax', [PeriodeController::class, 'import_ajax']); // Proses import
        Route::get('/export_excel', [PeriodeController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [PeriodeController::class, 'export_pdf']);
        Route::get('/export_template', [PeriodeController::class, 'exportTemplate']);
    });

    Route::group(['prefix' => 'matkul', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [MataKuliahController::class, 'index']);
        Route::post('/list', [MataKuliahController::class, 'list']);
        Route::get('/create_ajax', [MataKuliahController::class, 'create_ajax']);
        Route::post('/ajax', [MataKuliahController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [MataKuliahController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [MataKuliahController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [MataKuliahController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [MataKuliahController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [MataKuliahController::class, 'delete_ajax']);
        Route::get('/import', [MataKuliahController::class, 'import']);                      // ajax form upload excel
        Route::post('/import_ajax', [MataKuliahController::class, 'import_ajax']);           // ajax import excel
        Route::get('/export_excel', [MataKuliahController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [MataKuliahController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'jenis', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [JenisController::class, 'index']);
        Route::post('/list', [JenisController::class, 'list']);
        Route::get('/create_ajax', [JenisController::class, 'create_ajax']);
        Route::post('/ajax', [JenisController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [JenisController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [JenisController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [JenisController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [JenisController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [JenisController::class, 'delete_ajax']);
        Route::get('/import', [JenisController::class, 'import']);                      // ajax form upload excel
        Route::post('/import_ajax', [JenisController::class, 'import_ajax']);           // ajax import excel
        Route::get('/export_excel', [JenisController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [JenisController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'pelatihan', 'middleware'=> 'authorize:ADM,PMN,DSN'], function(){
        Route::get('/', [PelatihanController::class, 'index']);
        Route::post('/list', [PelatihanController::class, 'list']);
        Route::get('/create_ajax', [PelatihanController::class, 'create_ajax']);
        Route::post('/ajax', [PelatihanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [PelatihanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [PelatihanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [PelatihanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [PelatihanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [PelatihanController::class, 'delete_ajax']);
        Route::get('/import', [PelatihanController::class, 'import']); // Form import
        Route::post('/import_ajax', [PelatihanController::class, 'import_ajax']); // Proses import
        Route::get('/export_template', [PelatihanController::class, 'exportTemplate']);
        Route::get('/export_excel', [PelatihanController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [PelatihanController::class, 'export_pdf']);
        Route::get('/{id}/tambah_peserta', [PelatihanController::class, 'tambah_peserta']);
        Route::post('/{id}/kirim', [PelatihanController::class, 'kirim']);
        
    });

    Route::group(['prefix' => 'sertifikasi', 'middleware'=> 'authorize:ADM,PMN,DSN'], function(){
        Route::get('/', [SertifikasiController::class, 'index']);
        Route::post('/list', [SertifikasiController::class, 'list']);
        Route::get('/create_ajax', [SertifikasiController::class, 'create_ajax']);
        Route::post('/ajax', [SertifikasiController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [SertifikasiController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [SertifikasiController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [SertifikasiController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax',[SertifikasiController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [SertifikasiController::class, 'delete_ajax']);
        Route::get('/import', [SertifikasiController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [SertifikasiController::class, 'import_ajax']);
        Route::get('/export_template', [SertifikasiController::class, 'exportTemplate']); // ajax import excel         // ajax import excel
        Route::get('/export_excel', [SertifikasiController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [SertifikasiController::class, 'export_pdf']);
        Route::get('/{id}/tambah_peserta', [SertifikasiController::class, 'tambah_peserta']);
        Route::post('/{id}/kirim', [SertifikasiController::class, 'kirim']);
    });

    Route::group(['prefix' => 'vendor', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [VendorController::class, 'index']);
        Route::post('/list', [VendorController::class, 'list']);
        Route::get('/create_ajax', [VendorController::class, 'create_ajax']);
        Route::post('/ajax', [VendorController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [VendorController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [VendorController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [VendorController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax',[VendorController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [VendorController::class, 'delete_ajax']);
        Route::get('/import', [VendorController::class, 'import']);                      // ajax form upload excel
        Route::post('/import_ajax', [VendorController::class, 'import_ajax']);           // ajax import excel
        Route::get('/export_excel', [VendorController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [VendorController::class, 'export_pdf']);
        Route::get('/export_template', [VendorController::class, 'exportTemplate']);
    });

    Route::group(['prefix' => 'view_dosen', 'middleware'=> 'authorize:ADM,PMN,DSN'], function(){
        Route::get('/', [DaftarDosenController::class, 'index']);
        Route::post('list', [DaftarDosenController::class, 'list']);
        Route::get('/create_ajax', [DaftarDosenController::class, 'create_ajax']);
        Route::post('/ajax', [DaftarDosenController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [DaftarDosenController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [DaftarDosenController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [DaftarDosenController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [DaftarDosenController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [DaftarDosenController::class, 'delete_ajax']);
        Route::get('/import', [DaftarDosenController::class, 'import']);                      // ajax form upload excel
        Route::post('/import_ajax', [DaftarDosenController::class, 'import_ajax']);           // ajax import excel
        Route::get('/export_excel', [DaftarDosenController::class, 'export_excel']);          // ajax import excel
        Route::get('/export_pdf', [DaftarDosenController::class, 'export_pdf']);
        Route::get('/export_template', [DaftarDosenController::class, 'exportTemplate']);
    });


    Route::group(['prefix' => 'upload_pelatihan', 'middleware'=> 'authorize:DSN'], function(){
        Route::get('/', [UploadPelatihanController::class, 'index']);
        Route::post('list', [UploadPelatihanController::class, 'list']);
        Route::get('/create_ajax', [UploadPelatihanController::class, 'create_ajax']);
        Route::post('/ajax', [UploadPelatihanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [UploadPelatihanController::class, 'show_ajax']); // route untuk detail
        Route::get('/{id}/edit_ajax', [UploadPelatihanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [UploadPelatihanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [UploadPelatihanController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [UploadPelatihanController::class, 'delete_ajax']);
});

    Route::group(['prefix' => 'acc_daftar', 'middleware'=> 'authorize:PMN'], function(){
        Route::get('/', [ValidasiController::class, 'index']);
        Route::get('/list', [ValidasiController::class, 'list']);
        Route::get('/{id}/show_pelatihan_ajax', [ValidasiController::class, 'show_pelatihan_ajax']);
        Route::get('/{id}/show_sertifikasi_ajax', [ValidasiController::class, 'show_sertifikasi_ajax']);
        Route::post('/{id}/validasi', [ValidasiController::class, 'validasi']);
    });

    Route::group(['prefix' => 'surat_tugas', 'middleware'=> 'authorize:ADM,DSN'], function(){
        Route::get('/', [SuratController::class, 'index']);
        Route::get('/list', [SuratController::class, 'list']);
        Route::get('/{id}/show_pelatihan_ajax', [SuratController::class, 'show_pelatihan_ajax']);
        Route::get('/{id}/show_sertifikasi_ajax', [SuratController::class, 'show_sertifikasi_ajax']);
        Route::post('/store', [SuratController::class, 'store']);
        Route::post('/{id}/validasi', [SuratController::class, 'validasi']);
        Route::get('/download/{id}', [SuratController::class, 'download']); // Pastikan path ini benar
    });


    Route::post('/notifications/{id}/read', function($id) {
        try {
            NotifikasiModel::where('id', $id)->update(['is_read' => true]);
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
    })->name('notifications.read');
    
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

});
