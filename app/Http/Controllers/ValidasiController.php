<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel;
use App\Models\NotifikasiModel;
use App\Models\PesertaModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // import excel
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // import pdf
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengajuan',
            'list' => ['Home', 'Pengajuan']
        ];
        
        $page = (object) [
            'title' => 'Daftar pengajuan yang terdaftar dalam sistem',
        ];
     
        $activeMenu = 'pengajuan'; 
        $peserta = PesertaModel::with(['user', 'pelatihan'])
        ->where('status', 'Pending')
        ->get();
     
        return view('validasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'peserta' => $peserta
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 
        $peserta = PesertaModel::with(['user', 'pelatihan'])
            ->select('pelatihan_id', DB::raw('MAX(status) as status')) // Ambil status terakhir
            ->groupBy('pelatihan_id')
            ->get();
    
        return DataTables::of($peserta) 
            ->addIndexColumn()
            ->addColumn('nama_pelatihan', function ($peserta) {
                return $peserta->pelatihan->nama_pelatihan ?? '-';
            })
            ->addColumn('tanggal', function ($peserta) {
                return date('d/m/Y', strtotime($peserta->pelatihan->tanggal));
            })
            ->addColumn('tanggal_acc', function ($peserta) {
                return $peserta->status == 'Approved' ? date('d/m/Y', strtotime($peserta->updated_at)) : '-';
            })
            ->addColumn('aksi', function ($peserta) {  
                if ($peserta->status == 'Pending') {
                    return '<button onclick="modalAction(\'' . url('/acc_daftar/' . $peserta->pelatihan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                } else {
                    return '<button class="btn btn-secondary btn-sm" disabled>Sudah Disetujui</button>';
                }
            }) 
            ->rawColumns(['aksi',])
            ->make(true); 
    }

    public function show_ajax(string $id)
    {
        $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->find($id);
        
        // Ambil hanya data peserta dan namanya
        $peserta = PesertaModel::with(['user:user_id,nama'])
            ->where('pelatihan_id', $id)
            ->get();
        
        return view('validasi.show_ajax', [
            'pelatihan' => $pelatihan,
            'peserta' => $peserta
        ]);
    }

    public function validasi(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::beginTransaction();

                // Pastikan pelatihan ada
                $pelatihan = PelatihanModel::findOrFail($id);
                
                // Update status semua peserta untuk pelatihan ini
                DB::table('peserta_pelatihan')
                    ->where('pelatihan_id', $id)
                    ->where('status', 'Pending')
                    ->update([
                        'status' => $request->status,
                        'updated_at' => now()
                    ]);

                // Jika status disetujui, kirim notifikasi ke setiap dosen
                if ($request->status === 'Approved') {
                    $peserta = PesertaModel::where('pelatihan_id', $id)
                        ->where('status', 'Approved')
                        ->get();

                    foreach ($peserta as $p) {
                        NotifikasiModel::create([
                            'user_id' => $p->user_id,
                            'title' => 'Pelatihan Disetujui',
                            'message' => "Pengajuan pelatihan {$pelatihan->nama_pelatihan} telah disetujui",
                            'type' => 'approval_pelatihan',
                            'reference_id' => $id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => $request->status === 'Approved' ? 
                        'Pelatihan berhasil disetujui' : 
                        'Pelatihan ditolak'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Validasi Error: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/');
    }
}
