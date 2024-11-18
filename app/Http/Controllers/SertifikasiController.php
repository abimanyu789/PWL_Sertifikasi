<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SertifikasiModel;
use App\Models\JenisSertifikasiModel;
use App\Models\BidangModel;

class SertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Sertifikasi',
            'list' => ['Home', 'Sertifikasi']
        ];
        $page = (object) [
            'title' => 'Daftar sertifikasi yang terdaftar dalam sistem',
        ];
        $activeMenu = 'sertifikasi';
        $jenis = JenisSertifikasiModel::all(); // Ambil data jenis sertifikasi untuk dropdown filter
        return view('data_sertifikasi.sertifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenis' => $jenis,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data sertifikasi dalam bentuk JSON untuk DataTables
    public function list(Request $request) 
    { 
        $sertifikasi = SertifikasiModel::select('sertifikasi_id', 'nama_sertifikasi', 'tanggal', 'bidang_id', 'jenis_id', 'tanggal_berlaku')
                    ->with(['bidang', 'jenis']); // Mengambil relasi bidang dan jenis
    
        return DataTables::of($sertifikasi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($sertifikasi) {  
                $btn  = '<a href="'.url('/sertifikasi/' . $sertifikasi->sertifikasi_id).'" class="btn btn-info btn-sm">Detail</a> '; 
                $btn .= '<a href="'.url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/sertifikasi/'.$sertifikasi->sertifikasi_id).'">' 
                        . csrf_field() . method_field('DELETE') .  
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';      
                return $btn; 
            })
            ->rawColumns(['aksi'])
            ->make(true);
    } 

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Sertifikasi',
            'list' => ['Home', 'Sertifikasi', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Form Tambah Sertifikasi',
        ];
        $jenis = JenisSertifikasiModel::all(); // Ambil data jenis untuk dropdown
        $bidang = BidangModel::all(); // Ambil data bidang untuk dropdown
        $activeMenu = 'sertifikasi';
        return view('data_sertifikasi.sertifikasi.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenis' => $jenis,
            'bidang' => $bidang,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sertifikasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'bidang_id' => 'required|integer',
            'jenis_id' => 'required|integer',
            'tanggal_berlaku' => 'required|date'
        ]);

        SertifikasiModel::create($request->all());
        return redirect('/sertifikasi')->with('success', 'Data sertifikasi berhasil disimpan');
    }

    public function show($id)
    {
        $sertifikasi = SertifikasiModel::with(['bidang', 'jenis'])->findOrFail($id);
        $breadcrumb = (object) [
            'title' => 'Detail Sertifikasi',
            'list' => ['Home', 'Sertifikasi', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail Sertifikasi'
        ];
        $activeMenu = 'sertifikasi';
        return view('data_sertifikasi.sertifikasi.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'sertifikasi' => $sertifikasi,
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit($id)
    {
        $sertifikasi = SertifikasiModel::findOrFail($id);
        $bidang = BidangModel::all();
        $jenis = JenisSertifikasiModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Sertifikasi',
            'list' => ['Home', 'Sertifikasi', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit Data Sertifikasi'
        ];
        $activeMenu = 'sertifikasi';

        return view('data_sertifikasi.sertifikasi.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'sertifikasi' => $sertifikasi,
            'bidang' => $bidang,
            'jenis' => $jenis,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sertifikasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'bidang_id' => 'required|integer',
            'jenis_id' => 'required|integer',
            'tanggal_berlaku' => 'required|date'
        ]);

        $sertifikasi = SertifikasiModel::findOrFail($id);
        $sertifikasi->update($request->all());
        return redirect('/sertifikasi')->with('success', 'Data sertifikasi berhasil diupdate');
    }

    public function destroy(string $id)
    {
        // Cek apakah data user dengan ID yang dimaksud ada atau tidak
        $check = SertifikasiModel::find($id);
        if (!$check) {
            return redirect('/sertifikasi')->with('error', 'Data sertifikasi tidak ditemukan');
        }
        try {
            // Hapus data pelatihan
            SertifikasiModel::destroy($id);
            return redirect('/sertifikasi')->with('success', 'Data sertifikasi berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/sertifikasi')->with('error', 'Data sertifikasi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}