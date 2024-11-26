<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SertifikasiController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'no_sertif' => 'required|string',
                'nama_sertif' => 'required|string',
                'tanggal_pelaksanaan' => 'required|date',
                'tanggal_berlaku' => 'required|date',
                'bidang_id' => 'required|exists:bidang,bidang_id',
                'vendor_id' => 'nullable|exists:vendor,vendor_id',
                'file_sertif' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
            ]);

            // Handle file upload
            if ($request->hasFile('file_sertif')) {
                $file = $request->file('file_sertif');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('sertifikat', $fileName, 'public');
            }

            // Insert data sertifikasi
            $sertifikasiId = DB::table('sertifikasi')->insertGetId([
                'user_id' => $request->user_id,
                'no_sertif' => $request->no_sertif,
                'nama_sertif' => $request->nama_sertif,
                'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
                'tanggal_berlaku' => $request->tanggal_berlaku,
                'bidang_id' => $request->bidang_id,
                'vendor_id' => $request->vendor_id,
                'file_path' => $filePath ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sertifikasi berhasil ditambahkan',
                'data' => [
                    'sertifikasi_id' => $sertifikasiId,
                    'file_path' => $filePath ?? null
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan sertifikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}