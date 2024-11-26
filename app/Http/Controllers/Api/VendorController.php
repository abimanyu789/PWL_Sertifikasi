<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        try {
            $vendor = DB::table('m_vendor')
                ->select('vendor_id', 'vendor_nama', 'alamat', 'kota', 'no_telp', 'alamat_web')
                ->orderBy('vendor_nama')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $vendor
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'vendor_nama' => 'required|string|max:100',
                'alamat' => 'required|string|max:100',
                'kota' => 'required|string|max:100',
                'no_telp' => 'required|string|max:20',
                'alamat_web' => 'nullable|string|max:200'
            ]);

            $vendorId = DB::table('m_vendor')->insertGetId([
                'vendor_nama' => $request->vendor_nama,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'no_telp' => $request->no_telp,
                'alamat_web' => $request->alamat_web,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Vendor berhasil ditambahkan',
                'data' => [
                    'vendor_id' => $vendorId,
                    'vendor_nama' => $request->vendor_nama
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan vendor: ' . $e->getMessage()
            ], 500);
        }
    }
}