<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UploadPelatihanModel;
use App\Models\UploadSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SertifikatController extends Controller
{
    public function index()
    {
        // Ambil data dari tabel upload_pelatihan hanya untuk user yang sedang login
        $dataPelatihan = UploadPelatihanModel::with(['user', 'jenis'])
            ->where('user_id', Auth::id()) // Filter berdasarkan user yang sedang login
            ->get()
            ->map(function ($pelatihan) {
                return [
                    'upload_id' => $pelatihan->upload_id,
                    'user_id' => $pelatihan->user_id,
                    'nama_sertif' => $pelatihan->nama_sertif,
                    'no_sertif' => $pelatihan->no_sertif,
                    'tanggal' => $pelatihan->tanggal,
                    'masa_berlaku' => $pelatihan->masa_berlaku,
                    'jenis' => $pelatihan->jenis ? [
                        'jenis_id' => $pelatihan->jenis->jenis_id,
                        'jenis_nama' => $pelatihan->jenis->jenis_nama,
                    ] : null,
                    'nama_vendor' => $pelatihan->nama_vendor,
                    'bukti' => $pelatihan->bukti ? url('storage/pelatihan/' . $pelatihan->bukti) : null,
                ];
            });

        $dataSertifikasi = UploadSertifikasiModel::with(['user', 'jenis'])
            ->where('user_id', Auth::id()) // Filter berdasarkan user yang sedang login
            ->get()
            ->map(function ($sertifikasi) {
                return [
                    'upload_id' => $sertifikasi->upload_id,
                    'user_id' => $sertifikasi->user_id,
                    'nama_sertif' => $sertifikasi->nama_sertif,
                    'no_sertif' => $sertifikasi->no_sertif,
                    'tanggal' => $sertifikasi->tanggal,
                    'masa_berlaku' => $sertifikasi->masa_berlaku,
                    'jenis' => $sertifikasi->jenis ? [
                        'jenis_id' => $sertifikasi->jenis->jenis_id,
                        'jenis_nama' => $sertifikasi->jenis->jenis_nama,
                    ] : null,
                    'nama_vendor' => $sertifikasi->nama_vendor,
                    'bukti' => $sertifikasi->bukti ? url('storage/sertifikasi/' . $sertifikasi->bukti) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data_pelatihan' => $dataPelatihan,
            'data_sertifikasi' => $dataSertifikasi,
        ]);
    }

    public function show($id)
    {
        $userId = Auth::id();

        $pelatihan = UploadPelatihanModel::with(['user', 'jenis'])
            ->where('user_id', $userId)
            ->find($id);

        $sertifikasi = UploadSertifikasiModel::with(['user', 'jenis'])
            ->where('user_id', $userId)
            ->find($id);

        if (!$pelatihan && !$sertifikasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
            ], 404);
        }

        $dataPelatihan = $pelatihan ? [
            'upload_id' => $pelatihan->upload_id,
            'user_id' => $pelatihan->user_id,
            'nama_sertif' => $pelatihan->nama_sertif,
            'no_sertif' => $pelatihan->no_sertif,
            'tanggal' => $pelatihan->tanggal,
            'masa_berlaku' => $pelatihan->masa_berlaku,
            'jenis' => $pelatihan->jenis ? [
                'jenis_id' => $pelatihan->jenis->jenis_id,
                'jenis_nama' => $pelatihan->jenis->jenis_nama,
            ] : null,
            'nama_vendor' => $pelatihan->nama_vendor,
            'bukti' => $pelatihan->bukti ? url('storage/pelatihan/' . $pelatihan->bukti) : null,
        ] : null;

        $dataSertifikasi = $sertifikasi ? [
            'upload_id' => $sertifikasi->upload_id,
            'user_id' => $sertifikasi->user_id,
            'nama_sertif' => $sertifikasi->nama_sertif,
            'no_sertif' => $sertifikasi->no_sertif,
            'tanggal' => $sertifikasi->tanggal,
            'masa_berlaku' => $sertifikasi->masa_berlaku,
            'jenis' => $sertifikasi->jenis ? [
                'jenis_id' => $sertifikasi->jenis->jenis_id,
                'jenis_nama' => $sertifikasi->jenis->jenis_nama,
            ] : null,
            'nama_vendor' => $sertifikasi->nama_vendor,
            'bukti' => $sertifikasi->bukti ? url('storage/sertifikasi/' . $sertifikasi->bukti) : null,
        ] : null;

        return response()->json([
            'status' => 'success',
            'data_pelatihan' => $dataPelatihan,
            'data_sertifikasi' => $dataSertifikasi,
        ]);
    }

    public function hitungSertifikat()
    {
        $userId = Auth::id();

        $jumlahPelatihan = DB::table('upload_pelatihan')->where('user_id', $userId)->count();
        $jumlahSertifikasi = DB::table('upload_sertifikasi')->where('user_id', $userId)->count();

        // Menjumlahkan keduanya untuk mendapatkan jumlah sertifikat total
        $jumlahSertifikat = $jumlahPelatihan + $jumlahSertifikasi;

        return response()->json([
            'jumlah_sertifikat' => $jumlahSertifikat
        ]);
    }
}
