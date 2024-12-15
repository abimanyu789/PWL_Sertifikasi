<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanModel;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index()
    {
        // Gunakan eager loading untuk mengambil data relasi yang relevan
        $data = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])
            ->get()
            ->map(function ($pelatihan) {
                return [
                    'pelatihan_id' => $pelatihan->pelatihan_id,
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'deskripsi' => $pelatihan->deskripsi,
                    'tanggal' => $pelatihan->tanggal,
                    'lokasi' => $pelatihan->lokasi,
                    'biaya' => $pelatihan->biaya,
                    'kuota' => $pelatihan->kuota,
                    'level_pelatihan' => $pelatihan->level_pelatihan,
                        'vendor' => $pelatihan->vendor ? [
                            'vendor_id' => $pelatihan->vendor->vendor_id,
                            'vendor_nama' => $pelatihan->vendor->vendor_nama,
                        ] : null,
                    'jenis' => $pelatihan->jenis ? [
                        'jenis_id' => $pelatihan->jenis->jenis_id,
                        'jenis_nama' => $pelatihan->jenis->jenis_nama,
                    ] : null
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        // Gunakan eager loading untuk detail pelatihan
        $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])
            ->findOrFail($id);

        $data = [
            'pelatihan_id' => $pelatihan->pelatihan_id,
            'nama_pelatihan' => $pelatihan->nama_pelatihan,
            'deskripsi' => $pelatihan->deskripsi,
            'tanggal' => $pelatihan->tanggal,
            'lokasi' => $pelatihan->lokasi,
            'biaya' => $pelatihan->biaya,
            'kuota' => $pelatihan->kuota,
            'level_pelatihan' => $pelatihan->level_pelatihan,
            'vendor' => $pelatihan->vendor ? [
                'vendor_id' => $pelatihan->vendor->vendor_id,
                'vendor_nama' => $pelatihan->vendor->vendor_nama,
            ] : null,
            'jenis' => $pelatihan->jenis ? [
                'jenis_id' => $pelatihan->jenis->jenis_id,
                'jenis_nama' => $pelatihan->jenis->jenis_nama,
            ] : null
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
