<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;

class SertifikasiController extends Controller
{
    public function index()
    {
        // Gunakan eager loading untuk mengambil data bidang dan jenis sekaligus
        $data = SertifikasiModel::with(['bidang', 'jenis_sertifikasi'])
            ->get()
            ->map(function ($sertifikasi) {
                return [
                    'sertifikasi_id' => $sertifikasi->sertifikasi_id,
                    'nama_sertifikasi' => $sertifikasi->nama_sertifikasi,
                    'tanggal' => $sertifikasi->tanggal,
                    'tanggal_berlaku' => $sertifikasi->tanggal_berlaku,
                    'bidang_id' => $sertifikasi->bidang_id,
                    'bidang_nama' => $sertifikasi->bidang ? $sertifikasi->bidang->bidang_nama : null,
                    'jenis_id' => $sertifikasi->jenis_id,
                    'jenis_nama' => $sertifikasi->jenis_sertifikasi ? $sertifikasi->jenis_sertifikasi->jenis_nama : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        // Gunakan eager loading untuk detail sertifikasi
        $sertifikasi = SertifikasiModel::with(['bidang', 'jenis_sertifikasi'])
            ->findOrFail($id);

        $data = [
            'sertifikasi_id' => $sertifikasi->sertifikasi_id,
            'nama_sertifikasi' => $sertifikasi->nama_sertifikasi,
            'tanggal' => $sertifikasi->tanggal,
            'tanggal_berlaku' => $sertifikasi->tanggal_berlaku,
            'bidang_id' => $sertifikasi->bidang_id,
            'bidang_nama' => $sertifikasi->bidang ? $sertifikasi->bidang->bidang_nama : null,
            'jenis_id' => $sertifikasi->jenis_id,
            'jenis_nama' => $sertifikasi->jenis_sertifikasi ? $sertifikasi->jenis_sertifikasi->jenis_nama : null,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}