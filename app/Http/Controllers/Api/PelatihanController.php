<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanModel;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index()
    {
        // Gunakan eager loading untuk mengambil data bidang, level pelatihan, dan vendor sekaligus
        $data = PelatihanModel::with(['bidang', 'level_pelatihan', 'vendor'])
            ->get()
            ->map(function ($pelatihan) {
                return [
                    'pelatihan_id' => $pelatihan->pelatihan_id,
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'deskripsi' => $pelatihan->deskripsi,
                    'tanggal' => $pelatihan->tanggal,
                    'bidang_id' => $pelatihan->bidang_id,
                    'bidang_nama' => $pelatihan->bidang ? $pelatihan->bidang->bidang_nama : null,
                    'level_pelatihan_id' => $pelatihan->level_pelatihan_id,
                    'level_pelatihan_nama' => $pelatihan->level_pelatihan ? $pelatihan->level_pelatihan->level_pelatihan_nama : null,
                    'vendor_id' => $pelatihan->vendor_id,
                    'vendor_nama' => $pelatihan->vendor ? $pelatihan->vendor->vendor_nama : null,
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
        $pelatihan = PelatihanModel::with(['bidang', 'level_pelatihan', 'vendor'])
            ->findOrFail($id);

        $data = [
            'pelatihan_id' => $pelatihan->pelatihan_id,
            'nama_pelatihan' => $pelatihan->nama_pelatihan,
            'deskripsi' => $pelatihan->deskripsi,
            'tanggal' => $pelatihan->tanggal,
            'bidang_id' => $pelatihan->bidang_id,
            'bidang_nama' => $pelatihan->bidang ? $pelatihan->bidang->bidang_nama : null,
            'level_pelatihan_id' => $pelatihan->level_pelatihan_id,
            'level_pelatihan_nama' => $pelatihan->level_pelatihan ? $pelatihan->level_pelatihan->level_pelatihan_nama : null,
            'vendor_id' => $pelatihan->vendor_id,
            'vendor_nama' => $pelatihan->vendor ? $pelatihan->vendor->vendor_nama : null,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
