<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UploadPelatihanModel;
use App\Models\UploadSertifikasiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        try {
            // Mengambil data user dengan level_id = 3
            $dosen = UserModel::where('level_id', 3)->get();

            // Mengembalikan response sukses
            return response()->json([
                'success' => true,
                'message' => 'Data dosen dengan level_id = 3 berhasil diambil',
                'data' => $dosen
            ], 200);
        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi masalah
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dosen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Mengambil data dosen berdasarkan user_id dengan relasi terkait
            $dosen = UserModel::with([
                'level',              // Relasi ke level
                'bidang',             // Relasi ke bidang
                'mata_kuliah',        // Relasi ke mata kuliah
                'dosen',              // Relasi ke dosen
            ])
            ->where('user_id', $id)
            ->firstOrFail();  // Jika tidak ditemukan, akan melempar exception

            // Ambil data pelatihan
            $dataPelatihan = UploadPelatihanModel::with(['jenis'])
                ->where('user_id', $id) // Filter berdasarkan user_id
                ->get()
                ->map(function ($pelatihan) {
                    return [
                        'upload_id' => $pelatihan->upload_id,
                        'nama_sertif' => $pelatihan->nama_sertif,
                        'no_sertif' => $pelatihan->no_sertif,
                        'jenis' => $pelatihan->jenis ? [
                            'jenis_id' => $pelatihan->jenis->jenis_id,
                            'jenis_nama' => $pelatihan->jenis->jenis_nama,
                        ] : null,
                        'nama_vendor' => $pelatihan->nama_vendor,
                    ];
                });

            // Ambil data sertifikasi
            $dataSertifikasi = UploadSertifikasiModel::with(['jenis'])
                ->where('user_id', $id) // Filter berdasarkan user_id
                ->get()
                ->map(function ($sertifikasi) {
                    return [
                        'upload_id' => $sertifikasi->upload_id,
                        'nama_sertif' => $sertifikasi->nama_sertif,
                        'no_sertif' => $sertifikasi->no_sertif,
                        'jenis' => $sertifikasi->jenis ? [
                            'jenis_id' => $sertifikasi->jenis->jenis_id,
                            'jenis_nama' => $sertifikasi->jenis->jenis_nama,
                        ] : null,
                        'nama_vendor' => $sertifikasi->nama_vendor,
                    ];
                });

            // Menyiapkan data untuk response
            $data = [
                'user_id' => $dosen->user_id,
                'nip' => $dosen->nip,
                'nama' => $dosen->nama,
                'username' => $dosen->username,
                'email' => $dosen->email,
                'avatar' => $dosen->avatar,
                'level' => [
                    'level_id' => $dosen->level->level_id,
                    'level_nama' => $dosen->level->level_nama,
                ],
                'bidang' => $dosen->bidang ? [
                    'bidang_id' => $dosen->bidang->bidang_id,
                    'bidang_nama' => $dosen->bidang->bidang_nama
                ] : null,
                'mata_kuliah' => $dosen->mata_kuliah ? [
                    'mk_id' => $dosen->mata_kuliah->mk_id,
                    'mk_nama' => $dosen->mata_kuliah->mk_nama
                ] : null,
                'pelatihan' => $dataPelatihan,
                'sertifikasi' => $dataSertifikasi,
            ];

            // Mengembalikan response sukses dengan data dosen
            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi masalah
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dosen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
