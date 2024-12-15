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
        $data = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])
            ->get()
            ->map(function ($sertifikasi) {
                return [
                    'sertifikasi_id' => $sertifikasi->sertifikasi_id,
                    'nama_sertifikasi' => $sertifikasi->nama_sertifikasi,
                    'deskripsi' => $sertifikasi->deskripsi,
                    'kuota' => $sertifikasi->kuota,
                    'tanggal' => $sertifikasi->tanggal,
                    'level_sertifikasi' => $sertifikasi->level_sertifikasi,
                    'vendor' => $sertifikasi->vendor ? [
                        'vendor_id' => $sertifikasi->vendor->vendor_id,
                        'vendor_nama' => $sertifikasi->vendor->vendor_nama,
                    ] : null,
                    'jenis' => $sertifikasi->jenis ? [
                        'jenis_id' => $sertifikasi->jenis->jenis_id,
                        'jenis_nama' => $sertifikasi->jenis->jenis_nama,
                    ] : null,
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
        $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])
            ->findOrFail($id);

        $data = [
            'sertifikasi_id' => $sertifikasi->sertifikasi_id,
            'nama_sertifikasi' => $sertifikasi->nama_sertifikasi,
            'deskripsi' => $sertifikasi->deskripsi,
            'kuota' => $sertifikasi->kuota,
            'tanggal' => $sertifikasi->tanggal,
            'level_sertifikasi' => $sertifikasi->level_sertifikasi,
            'vendor' => $sertifikasi->vendor ? [
                'vendor_id' => $sertifikasi->vendor->vendor_id,
                'vendor_nama' => $sertifikasi->vendor->vendor_nama,
            ] : null,
            'jenis' => $sertifikasi->jenis ? [
                'jenis_id' => $sertifikasi->jenis->jenis_id,
                'jenis_nama' => $sertifikasi->jenis->jenis_nama,
            ] : null,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}