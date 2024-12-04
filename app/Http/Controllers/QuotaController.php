<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\Quota;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class QuotaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kuota Pelatihan',
            'list' => ['Home', 'Kuota']
        ];
        $page = (object) [
            'title' => 'Kuota pelatihan yang terdaftar dalam sistem',
        ];
        $activeMenu = 'quota'; 
        return view('quota.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $pelatihan = PelatihanModel::select(
                'm_pelatihan.pelatihan_id', 
                'm_pelatihan.nama_pelatihan',
                DB::raw('COALESCE(SUM(m_quota.quota_jumlah), 0) AS total_quota') 
            )
            ->leftJoin('m_quota', 'm_pelatihan.pelatihan_id', '=', 'm_quota.pelatihan_id') 
            ->groupBy('m_pelatihan.pelatihan_id', 'm_pelatihan.nama_pelatihan'); 

        return DataTables::of($pelatihan)
            ->addIndexColumn()
            ->addColumn('total_quota', function ($pelatihan) {
                return $pelatihan->total_quota;
            })
            ->addColumn('aksi', function ($pelatihan) {
                $btn = '<button onclick="modalAction(\'' . url('/quota/pelatihan/' . $pelatihan->pelatihan_id . '/add_ajax') . '\')" class="btn btn-info btn-sm">Tambah Kuota</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) 
            ->make(true); 
    }

    public function add_ajax($pelatihanId)
    {
        $pelatihan = PelatihanModel::findOrFail($pelatihanId);

        return view('quota.add_ajax', compact('pelatihan'))->render();
    }


    public function store_ajax(Request $request, $pelatihanId)
    {
        $request->validate([
            'quota_jumlah' => 'required|integer|min:1'
        ]);

        $pelatihan = PelatihanModel::find($pelatihanId);
        if (!$pelatihan) {
            return response()->json([
                'status' => false,
                'message' => 'Pelatihan tidak ditemukan.'
            ], 404);
        }

        try {
            Quota::create([
                'pelatihan_id' => $pelatihanId,
                'quota_jumlah' => $request->quota_jumlah,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Kuota berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
