<?php

namespace App\Http\Controllers;

use App\Models\UploadPelatihanModel;
use App\Models\UploadSertifikasiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index() 
{
    $breadcrumb = (object) [
        'title' => 'Dashboard',
        'list'  => ['Home','Dashboard']
    ];

    $activeMenu = 'dashboard';

    // Hitung total sertifikasi dan pelatihan
    $totalSertifikasi = UploadSertifikasiModel::select('upload_id')->count();
    $totalPelatihan = UploadPelatihanModel::select('upload_id')->count();

    // Hitung jumlah dosen unik yang pernah upload sertifikasi
$totalDosenSertifikasi = UploadSertifikasiModel::select('user_id')
->distinct()
->count('user_id');

// Hitung jumlah dosen unik yang pernah upload pelatihan
$totalDosenPelatihan = UploadPelatihanModel::select('user_id')
->distinct()
->count('user_id');

    // Hitung jumlah dosen (level_id = 3)
    $totalDosen = UserModel::where('level_id', 3)->count();

      $currentYear = date('Y');
    $lastYear = $currentYear - 1;

   // Hitung total upload tahun ini dari kedua tabel
$totalThisYearPelatihan = UploadPelatihanModel::whereYear('created_at', $currentYear)->count();
$totalThisYearSertifikasi = UploadSertifikasiModel::whereYear('created_at', $currentYear)->count();
$totalThisYear = $totalThisYearPelatihan + $totalThisYearSertifikasi;

// Hitung total upload tahun lalu dari kedua tabel
$totalLastYearPelatihan = UploadPelatihanModel::whereYear('created_at', $lastYear)->count();
$totalLastYearSertifikasi = UploadSertifikasiModel::whereYear('created_at', $lastYear)->count();
$totalLastYear = $totalLastYearPelatihan + $totalLastYearSertifikasi;

// Hitung persentase total
$total = $totalThisYear + $totalLastYear;
$percentThisYear = $total > 0 ? round(($totalThisYear / $total) * 100) : 0;
$percentLastYear = $total > 0 ? round(($totalLastYear / $total) * 100) : 0;

  // Hitung kontribusi bulanan untuk kedua tabel
$monthlyData = [];
$maxValue = 0;

// Hitung untuk setiap bulan di tahun sekarang
for ($i = 1; $i <= 12; $i++) {
   // Hitung total dari tabel pelatihan
   $countPelatihan = UploadPelatihanModel::whereYear('created_at', $currentYear)
       ->whereMonth('created_at', $i)
       ->count();
       
   // Hitung total dari tabel sertifikasi
   $countSertifikasi = UploadSertifikasiModel::whereYear('created_at', $currentYear)
       ->whereMonth('created_at', $i)
       ->count();
       
   // Jumlahkan kedua hasil
   $totalCount = $countPelatihan + $countSertifikasi;
   
   // Simpan total ke array
   $monthlyData[$i] = $totalCount;
   
   // Update nilai maksimum jika diperlukan
   $maxValue = max($maxValue, $totalCount);
}

   return view('welcome', [
       'breadcrumb' => $breadcrumb, 
       'activeMenu' => $activeMenu,
       'totalSertifikasi' => $totalSertifikasi,
       'totalPelatihan' => $totalPelatihan,
       'totalDosenSertifikasi' => $totalDosenSertifikasi,
    'totalDosenPelatihan' => $totalDosenPelatihan,
       'totalDosen' => $totalDosen,
       'currentYear' => $currentYear,
       'lastYear' => $lastYear,
       'totalThisYear' => $totalThisYear,
        'totalLastYear' => $totalLastYear,
        'totalThisYearSertifikasi' => $totalThisYearSertifikasi,
        'totalThisYearPelatihan' => $totalThisYearPelatihan,
        'totalLastYearSertifikasi' => $totalLastYearSertifikasi,
        'totalLastYearPelatihan' => $totalLastYearPelatihan,
       'percentThisYear' => $percentThisYear,
       'percentLastYear' => $percentLastYear,
       'monthlyData' => $monthlyData,  
       'maxValue' => $maxValue         
   ]);
  }
}