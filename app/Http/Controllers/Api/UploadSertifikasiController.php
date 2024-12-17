<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisModel;
use App\Models\UploadSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UploadSertifikasiController extends Controller
{
    public function getJenis()
    {
        $data = JenisModel::with([])
        ->get()
        ->map(function ($jenis) {
            return [
                'jenis_id' => $jenis->jenis_id,
                'jenis_nama' => $jenis->jenis_nama
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Store uploaded data via API.
     */
    public function store(Request $request)
    {
        // Validasi request
        $rules = [
            'nama_sertif' => 'required|string|max:255',
            'no_sertif' => 'required|string|max:255|unique:upload_sertifikasi,no_sertif',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'required|date|after:tanggal',
            'jenis_id' => 'required|exists:m_jenis,jenis_id',
            'nama_vendor' => 'required|string|max:255',
            'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $jenis = JenisModel::find($request->jenis_id);
            if (!$jenis) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jenis tidak ditemukan.'
                ], 404);
            }

            // Proses unggah file bukti
            $bukti = null;
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/sertifikasi', $fileName);
                $bukti = $fileName;
            }

            // Simpan data ke database
            UploadSertifikasiModel::create([
                'user_id' => Auth::id(),
                'nama_sertif' => $request->nama_sertif,
                'no_sertif' => $request->no_sertif,
                'tanggal' => $request->tanggal,
                'masa_berlaku' => $request->masa_berlaku,
                'jenis_id' => $request->jenis_id,
                'jenis_nama' => $jenis->nama_jenis,
                'nama_vendor' => $request->nama_vendor,
                'bukti' => $bukti,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Sertifikat sertifikasi berhasil disimpan.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
