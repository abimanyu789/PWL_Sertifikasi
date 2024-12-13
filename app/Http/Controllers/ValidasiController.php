<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel;
use App\Models\NotifikasiModel;
use App\Models\PesertaModel;
use App\Models\SertifikasiModel;
use App\Models\PesertaSertifikasiModel;
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
        
        // Data peserta pelatihan
        $peserta_pelatihan = PesertaModel::with(['user', 'pelatihan'])
            ->where('status', 'Pending')
            ->get();
            
        // Data peserta sertifikasi
        $peserta_sertifikasi = PesertaSertifikasiModel::with(['user', 'sertifikasi'])
            ->where('status', 'Pending')
            ->get();
     
        return view('validasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'peserta_pelatihan' => $peserta_pelatihan,
            'peserta_sertifikasi' => $peserta_sertifikasi
        ]);
    }

    public function list(Request $request) 
    { 
        try {
            // Ambil data pelatihan
            $pelatihan = PesertaModel::with(['user', 'pelatihan'])
                ->join('m_pelatihan', 'peserta_pelatihan.pelatihan_id', '=', 'm_pelatihan.pelatihan_id')
                ->select(
                    'peserta_pelatihan.pelatihan_id as id',
                    'm_pelatihan.nama_pelatihan as nama_kegiatan',
                    'm_pelatihan.tanggal',
                    DB::raw('MAX(peserta_pelatihan.status) as status'),
                    DB::raw('MAX(peserta_pelatihan.updated_at) as updated_at'),
                    DB::raw("'pelatihan' as jenis")
                )
                ->groupBy('peserta_pelatihan.pelatihan_id', 'm_pelatihan.nama_pelatihan', 'm_pelatihan.tanggal');
    
            // Ambil data sertifikasi
            $sertifikasi = PesertaSertifikasiModel::with(['user', 'sertifikasi'])
                ->join('m_sertifikasi', 'peserta_sertifikasi.sertifikasi_id', '=', 'm_sertifikasi.sertifikasi_id')
                ->select(
                    'peserta_sertifikasi.sertifikasi_id as id',
                    'm_sertifikasi.nama_sertifikasi as nama_kegiatan',
                    'm_sertifikasi.tanggal',
                    DB::raw('MAX(peserta_sertifikasi.status) as status'),
                    DB::raw('MAX(peserta_sertifikasi.updated_at) as updated_at'),
                    DB::raw("'sertifikasi' as jenis")
                )
                ->groupBy('peserta_sertifikasi.sertifikasi_id', 'm_sertifikasi.nama_sertifikasi', 'm_sertifikasi.tanggal');
    
            // Gabungkan kedua data
            $kombinasiData = $pelatihan->union($sertifikasi)
                ->orderBy('tanggal', 'desc')
                ->get();
    
            return DataTables::of($kombinasiData)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return date('d/m/Y', strtotime($row->tanggal));
                })
                ->addColumn('tanggal_acc', function ($row) {
                    return $row->status == 'Approved' ? date('d/m/Y', strtotime($row->updated_at)) : '-';
                })
                ->addColumn('aksi', function ($row) {  
                    if ($row->status == 'Pending') {
                        $url = url('/acc_daftar/' . $row->id . '/show_' . $row->jenis . '_ajax');
                        return '<button onclick="modalAction(\'' . $url . '\')" class="btn btn-info btn-sm">Detail</button>';
                    } else {
                        return '<button class="btn btn-secondary btn-sm" disabled>Sudah Disetujui</button>';
                    }
                })
                ->rawColumns(['aksi'])
                ->make(true);
    
        } catch (\Exception $e) {
            Log::error('Error in list method: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Menambahkan method untuk menampilkan detail sertifikasi
    public function show_sertifikasi_ajax(string $id)
{
    try {
        $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
        
        $peserta_sertifikasi = PesertaSertifikasiModel::with(['user:user_id,nama'])
            ->where('sertifikasi_id', $id)
            ->where('status', 'Pending')  // Tambahkan filter status
            ->get();
        
        // Tambahkan logging
        Log::info('Data sertifikasi:', ['id' => $id, 'data' => $sertifikasi->toArray()]);
        Log::info('Jumlah peserta:', ['count' => $peserta_sertifikasi->count()]);

        return view('validasi.show_sertifikasi_ajax', [
            'sertifikasi' => $sertifikasi,
            'peserta_sertifikasi' => $peserta_sertifikasi  // Sesuaikan nama variabel
        ]);
    } catch (\Exception $e) {
        Log::error('Error show sertifikasi: ' . $e->getMessage());
        throw $e;
    }
}

public function show_pelatihan_ajax(string $id)
{
    try {
        $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
        
        $peserta_pelatihan = PesertaModel::with(['user:user_id,nama'])
            ->where('pelatihan_id', $id)
            ->where('status', 'Pending')  // Tambahkan filter status
            ->get();
            
        // Tambahkan logging
        Log::info('Data pelatihan:', ['id' => $id, 'data' => $pelatihan->toArray()]);
        Log::info('Jumlah peserta:', ['count' => $peserta_pelatihan->count()]);

        return view('validasi.show_pelatihan_ajax', [
            'pelatihan' => $pelatihan,
            'peserta_pelatihan' => $peserta_pelatihan  // Sesuaikan nama variabel
        ]);
    } catch (\Exception $e) {
        Log::error('Error show pelatihan: ' . $e->getMessage());
        throw $e;
    }
}
    // public function validasi(Request $request, string $id)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         try {
    //             DB::beginTransaction();

    //             // Pastikan pelatihan ada
    //             $pelatihan = PelatihanModel::findOrFail($id);
                
    //             // Update status semua peserta untuk pelatihan ini
    //             DB::table('peserta_pelatihan')
    //                 ->where('pelatihan_id', $id)
    //                 ->where('status', 'Pending')
    //                 ->update([
    //                     'status' => $request->status,
    //                     'updated_at' => now()
    //                 ]);

    //             // Jika status disetujui, kirim notifikasi ke setiap dosen
    //             if ($request->status === 'Approved') {
    //                 $peserta = PesertaModel::where('pelatihan_id', $id)
    //                     ->where('status', 'Approved')
    //                     ->get();

    //                 foreach ($peserta as $p) {
    //                     NotifikasiModel::create([
    //                         'user_id' => $p->user_id,
    //                         'title' => 'Pelatihan Disetujui',
    //                         'message' => "Pengajuan pelatihan {$pelatihan->nama_pelatihan} telah disetujui",
    //                         'type' => 'approval_pelatihan',
    //                         'reference_id' => $id,
    //                         'created_at' => now(),
    //                         'updated_at' => now()
    //                     ]);
    //                 }
    //             }

    //             DB::commit();

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => $request->status === 'Approved' ? 
    //                     'Pelatihan berhasil disetujui' : 
    //                     'Pelatihan ditolak'
    //             ]);

    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             Log::error('Validasi Error: ' . $e->getMessage());
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //             ]);
    //         }
    //     }

    //     return redirect('/');
    // }

    public function validasi(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::beginTransaction();

                $kegiatan = $request->input('kegiatan', 'pelatihan');

                if ($kegiatan === 'pelatihan') {
                    $pelatihan = PelatihanModel::findOrFail($id);
                    
                    DB::table('peserta_pelatihan')
                        ->where('pelatihan_id', $id)
                        ->where('status', 'Pending')
                        ->update([
                            'status' => $request->status,
                            'updated_at' => now()
                        ]);

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
                } else {
                    $sertifikasi = SertifikasiModel::findOrFail($id);
                    
                    DB::table('peserta_sertifikasi')
                        ->where('sertifikasi_id', $id)
                        ->where('status', 'Pending')
                        ->update([
                            'status' => $request->status,
                            'updated_at' => now()
                        ]);

                    if ($request->status === 'Approved') {
                        $peserta = PesertaSertifikasiModel::where('sertifikasi_id', $id)
                            ->where('status', 'Approved')
                            ->get();

                        foreach ($peserta as $p) {
                            NotifikasiModel::create([
                                'user_id' => $p->user_id,
                                'title' => 'Sertifikasi Disetujui',
                                'message' => "Pengajuan sertifikasi {$sertifikasi->nama_sertifikasi} telah disetujui",
                                'type' => 'approval_sertifikasi',
                                'reference_id' => $id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => $request->status === 'Approved' ? 
                        ($kegiatan === 'pelatihan' ? 'Pelatihan berhasil disetujui' : 'Sertifikasi berhasil disetujui') : 
                        ($kegiatan === 'pelatihan' ? 'Pelatihan ditolak' : 'Sertifikasi ditolak')
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
