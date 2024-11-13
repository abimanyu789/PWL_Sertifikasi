<?php

namespace App\Http\Controllers;

use App\Models\LevelPelatihanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LevelPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level Pelatihan',
            'list' => ['Home', 'Level Pelatihan']
        ];
        $page = (object) [
            'title' => 'Daftar level pelatihan yang terdaftar dalam sistem'
        ];
        $activeMenu = 'level_pelatihan'; // set menu yang sedang aktif
        $level_pelatihan = LevelPelatihanModel::all();
        return view('level_pelatihan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level_pelatihan' => $level_pelatihan, 'activeMenu' => $activeMenu]);
    }
    
    public function list(Request $request)
    {
        $level_pelatihans = LevelPelatihanModel::select('level_pelatihan_id', 'level_pelatihan_kode', 'level_pelatihan_nama');
        return DataTables::of($level_pelatihans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level_pelatihan) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/level_pelatihan/' . $level_pelatihan->level_pelatihan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level_pelatihan/' . $level_pelatihan->level_pelatihan_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level_pelatihan/' . $level_pelatihan->level_pelatihan_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
}
