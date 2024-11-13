<?php

namespace App\Http\Controllers;

use App\Models\BidangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BidangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Bidang',
            'list' => ['Home', 'Bidang']
        ];
        $page = (object) [
            'title' => 'Daftar bidang yang terdaftar dalam sistem'
        ];
        $activeMenu = 'bidang'; // set menu yang sedang aktif
        $bidang = BidangModel::all();
        return view('bidang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'bidang' => $bidang, 'activeMenu' => $activeMenu]);
    }
    // Ambil data bidang dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $bidangs = BidangModel::select('bidang_id', 'bidang_kode', 'bidang_nama');
        return DataTables::of($bidangs)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($bidang) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/bidang/' . $bidang->bidang_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/bidang/' . $bidang->bidang_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/bidang/' . $bidang->bidang_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
}
