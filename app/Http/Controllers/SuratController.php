<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel;
use App\Models\MatkulModel;
use App\Models\JenisModel;
use App\Models\PeriodeModel;
use App\Models\NotifikasiModel;
use App\Models\SuratModel;
use App\Models\VendorModel; 
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // import excel
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // import pdf
use Illuminate\Support\Facades\DB;

class SuratController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Surat Tugas',
            'list' => ['Home', 'Surat Tugas']
        ];
        
        $page = (object) [
            'title' => 'Surat Tugas yang terdaftar dalam sistem',
        ];
     
        $activeMenu = 'surat_tugas'; 
        $surat_tugas = SuratModel::all();
     
        return view('data_pelatihan.pelatihan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'surat_tugas' => $surat_tugas,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 

        $surat_tugas = SuratModel::with(['peserta_sertifikasi_id', 'jenis', 'mata_kuliah', 'periode']);
        
        if ($request->level_pelatihan) {
            $pelatihan->where('level_pelatihan', $request->level_pelatihan);
        }
    
        return DataTables::of($pelatihan) 
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($pelatihan) {  // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->pelatihan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->pelatihan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->pelatihan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                
                // Hitung jumlah peserta yang sudah terdaftar
                $jumlah_peserta = DB::table('peserta_pelatihan')
                ->where('pelatihan_id', $pelatihan->pelatihan_id)
                ->count();

                // Tampilkan tombol tambah peserta hanya jika kuota belum penuh
                if ($jumlah_peserta < $pelatihan->kuota) {
                    $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->pelatihan_id . '/tambah_peserta') . '\')" class="btn btn-success btn-sm">Tambah Peserta</button>';
                } else {
                    $btn .= '<button class="btn btn-secondary btn-sm" disabled>Kuota Penuh</button>';
                }
                return $btn;
            }) 
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true); 
    }
    
}
