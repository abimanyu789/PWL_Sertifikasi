<?php

namespace App\Http\Controllers;

use App\Models\JenisSertifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JenisSertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Sertifikasi',
            'list' => ['Home', 'Jenis Sertifikasi']
        ];
        $page = (object) [
            'title' => 'Daftar jenis sertifikasi yang terdaftar dalam sistem'
        ];
        $activeMenu = 'jenis_sertifikasi'; // set menu yang sedang aktif
        $jenis_sertifikasi = JenisSertifikasiModel::all();
        return view('jenis_sertifikasi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jenis_sertifikasi' => $jenis_sertifikasi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $jenis_sertifikasis = JenisSertifikasiModel::select('jenis_id', 'jenis_kode', 'jenis_nama');
        return DataTables::of($jenis_sertifikasis)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis_sertifikasi) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/jenis_sertifikasi/' . $jenis_sertifikasi->jenis_sertifikasi_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis_sertifikasi/' . $jenis_sertifikasi->jenis_sertifikasi_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis_sertifikasi/' . $jenis_sertifikasi->jenis_sertifikasi_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
}
