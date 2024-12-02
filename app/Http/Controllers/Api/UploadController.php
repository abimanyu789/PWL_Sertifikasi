<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UploadModel;  // Sesuaikan dengan nama model Anda
use Illuminate\Support\Facades\Validator;  // Tambahkan ini
use Illuminate\Support\Facades\Storage;    // Tambahkan ini


class UploadController extends Controller
{
    // Mengambil semua data
    public function index()
    {
        $data = UploadModel::with(['user', 'bidang', 'vendor'])
            ->get()
            ->map(function ($upload) {
                return [
                    'sertif_id'=> $upload->sertif_id,
                    'user_id'=> $upload->user_id,
                    'no_sertif'=> $upload->no_sertif,
                    'nama_sertif'=> $upload->nama_sertif,
                    'tanggal_pelaksanaan'=> $upload->tanggal_pelaksanaan,
                    'tanggal_berlaku'=> $upload->tanggal_berlaku,
                    'bidang_id'=> $upload->bidang_id,
                    'vendor_id' => $upload->vendor_id,
                    'image' => $upload->image ? asset('storage/' . $upload->image) : null,
                ];
            });
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // Menambahkan data baru
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'no_sertif' => 'required|string|max:255',
            'nama_sertif' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|date',
            'tanggal_berlaku' => 'required|date|after_or_equal:tanggal_pelaksanaan',
            'bidang_id' => 'required|exists:bidangs,bidang_id',  // Validasi bidang_id
            'vendor_id' => 'nullable|exists:vendors,vendor_id',  // Validasi vendor_id
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Jika ada file gambar
        ]);

        // Upload dan simpan file
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public'); // Simpan ke storage/app/public/uploads
            $validated['image'] = $path; // Path disimpan dalam array $validated
        }

        // Simpan data ke database
        $upload = UploadModel::create([
            'user_id' => $request->user_id,
            'no_sertif' => $request->no_sertif,
            'nama_sertif' => $request->nama_sertif,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'bidang_id' => $request->bidang_id,
            'vendor_id' => $request->vendor_id,
            'image' => $request->image ? asset('storage/' . $request->image) : null,
        ]);

        // Load relasi untuk response
        $upload->load(['user', 'bidang', 'vendor']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data sertifikat berhasil disimpan',
            'data' => [
                'sertif_id'=> $upload->sertif_id,
                'user_id'=> $upload->user_id,
                'no_sertif'=> $upload->no_sertif,
                'nama_sertif'=> $upload->nama_sertif,
                'tanggal_pelaksanaan'=> $upload->tanggal_pelaksanaan,
                'tanggal_berlaku'=> $upload->tanggal_berlaku,
                'bidang_id'=> $upload->bidang_id,
                'vendor_id' => $upload->vendor_id,
                'image' => $upload->image ? asset('storage/' . $upload->image) : null,
            ]
        ], 201);
        // Simpan data ke dalam model

    }

    // Mengambil data untuk dropdown bidang dan vendor
    // public function getDropdownData()
    // {
    //     $bidangs = \App\Models\BidangModel::all();
    //     $vendors = \App\Models\VendorModel::all();

    //     return response()->json([
    //         'status' => 'success',
    //         'bidang' => $bidangs,
    //         'vendor' => $vendors
    //     ]);
    // }

}
