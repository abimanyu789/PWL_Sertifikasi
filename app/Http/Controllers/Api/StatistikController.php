<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function getStatistics()
    {
        try {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            
            // Total sertifikat tahun ini (menggunakan field tanggal)
            $totalTahunIni = DB::table('m_sertifikasi')
                ->whereYear('tanggal', $currentYear)
                ->count();

            // Sertifikat bulan ini
            $bulanIni = DB::table('m_sertifikasi')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $currentMonth)
                ->count();

            // Sertifikat bulan lalu
            $bulanLalu = DB::table('m_sertifikasi')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', Carbon::now()->subMonth()->month)
                ->count();

            // Debug query
            Log::info('SQL Queries:', [
                'total_query' => DB::table('m_sertifikasi')
                    ->whereYear('tanggal', $currentYear)
                    ->toSql(),
                'bulan_ini_query' => DB::table('m_sertifikasi')
                    ->whereYear('tanggal', $currentYear)
                    ->whereMonth('tanggal', $currentMonth)
                    ->toSql()
            ]);

            // Hitung trend
            $peningkatan = max(0, $bulanIni - $bulanLalu);
            $penurunan = max(0, $bulanLalu - $bulanIni);

            return response()->json([
                'success' => true,
                'data' => [
                    'tahun' => $currentYear,
                    'total_tahun_ini' => $totalTahunIni,
                    'bulan_ini' => $bulanIni,
                    'bulan_lalu' => $bulanLalu,
                    'peningkatan' => $peningkatan,
                    'penurunan' => $penurunan,
                    'bulan_sekarang' => Carbon::now()->locale('id')->monthName,
                    'bulan_kemarin' => Carbon::now()->subMonth()->locale('id')->monthName
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in statistics: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}