<?php

namespace App\Http\Controllers\Api;

<<<<<<< HEAD
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
=======
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

>>>>>>> 0f1a0778deebd95e558bae16a8bfcb49bb799121

class BidangController extends Controller
{
    public function index()
    {
        try {
            $bidang = DB::table('m_bidang')
                ->select('bidang_id', 'bidang_nama')
                ->orderBy('bidang_nama')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $bidang
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data bidang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'bidang_nama' => 'required|string|max:100',
                'bidang_kode' => 'required|string|max:10|unique:bidang'
            ]);

            $bidangId = DB::table('m_bidang')->insertGetId([
                'bidang_nama' => $request->bidang_nama,
                'bidang_kode' => $request->bidang_kode,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Bidang berhasil ditambahkan',
                'data' => [
                    'bidang_id' => $bidangId,
                    'bidang_nama' => $request->bidang_nama,
                    'bidang_kode' => $request->bidang_kode
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan bidang: ' . $e->getMessage()
            ], 500);
        }
    }
}