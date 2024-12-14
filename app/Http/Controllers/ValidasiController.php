<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel;
use App\Models\NotifikasiModel;
use App\Models\PesertaPelatihanModel;
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
        $peserta_pelatihan = PesertaPelatihanModel::with(['dosen', 'pelatihan'])
            ->where('status', 'Pending')
            ->get();
            
        // Data peserta sertifikasi
        $peserta_sertifikasi = PesertaSertifikasiModel::with(['dosen', 'sertifikasi'])
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
        $peserta_pelatihan = PesertaPelatihanModel::with(['pelatihan', 'dosen'])
            ->select(
                'pelatihan_id as id',
                DB::raw('(SELECT nama_pelatihan FROM m_pelatihan WHERE pelatihan_id = peserta_pelatihan.pelatihan_id) as nama_kegiatan'),
                DB::raw('(SELECT tanggal FROM m_pelatihan WHERE pelatihan_id = peserta_pelatihan.pelatihan_id) as tanggal'),
                DB::raw('MAX(status) as status'), // Menggunakan MAX untuk status
                DB::raw('MAX(updated_at) as updated_at'), // Menggunakan MAX untuk updated_at
                DB::raw("'pelatihan' as jenis")
            )
            ->groupBy('pelatihan_id');

        // Ambil data sertifikasi
        $peserta_sertifikasi = PesertaSertifikasiModel::with(['sertifikasi', 'dosen'])
            ->select(
                'sertifikasi_id as id',
                DB::raw('(SELECT nama_sertifikasi FROM m_sertifikasi WHERE sertifikasi_id = peserta_sertifikasi.sertifikasi_id) as nama_kegiatan'),
                DB::raw('(SELECT tanggal FROM m_sertifikasi WHERE sertifikasi_id = peserta_sertifikasi.sertifikasi_id) as tanggal'),
                DB::raw('MAX(status) as status'), // Menggunakan MAX untuk status
                DB::raw('MAX(updated_at) as updated_at'), // Menggunakan MAX untuk updated_at
                DB::raw("'sertifikasi' as jenis")
            )
            ->groupBy('sertifikasi_id');

        $kombinasiData = $peserta_pelatihan->union($peserta_sertifikasi)
            ->orderBy('tanggal', 'desc')
            ->get();

        return DataTables::of($kombinasiData)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($row) {
                return date('d/m/Y', strtotime($row->tanggal));
            })
            ->addColumn('tanggal_acc', function ($row) {
                if ($row->status === 'Approved') {
                    return '<span class="badge badge-success">' . 
                        \Carbon\Carbon::parse($row->updated_at)
                            ->setTimezone('Asia/Jakarta')
                            ->format('d/m/Y') . 
                        '</span>';
                } else if ($row->status === 'Rejected') {
                    return '<span class="badge badge-danger">' . 
                        \Carbon\Carbon::parse($row->updated_at)
                            ->setTimezone('Asia/Jakarta')
                            ->format('d/m/Y') . 
                        '</span>';
                } else {
                    return '<span class="badge badge-warning">Pending</span>';
                }
            })
            ->addColumn('aksi', function ($row) {
                $buttonClass = $row->status === 'Pending' ? 'btn-info' : 'btn-secondary';
                $url = url('/acc_daftar/' . $row->id . '/show_' . $row->jenis . '_ajax');
                return '<button onclick="modalAction(\'' . $url . '\')" class="btn ' . $buttonClass . ' btn-sm">Detail</button>';
            })
            ->rawColumns(['tanggal_acc', 'aksi'])
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
        
        $peserta_sertifikasi = $sertifikasi->peserta_sertifikasi()
            ->with(['dosen', 'dosen.user'])
            // Hapus where status Pending agar semua data tampil
            ->get();
        
        Log::info('Data sertifikasi:', ['id' => $id, 'data' => $sertifikasi->toArray()]);
        Log::info('Jumlah peserta:', ['count' => $peserta_sertifikasi->count()]);

        return view('validasi.show_sertifikasi_ajax', [
            'sertifikasi' => $sertifikasi,
            'peserta_sertifikasi' => $peserta_sertifikasi
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
        
        $peserta_pelatihan = $pelatihan->peserta_pelatihan()
            ->with(['dosen', 'dosen.user'])
            // Hapus where status Pending agar semua data tampil
            ->get();
        
        Log::info('Data pelatihan:', ['id' => $id, 'data' => $pelatihan->toArray()]);
        Log::info('Jumlah peserta:', ['count' => $peserta_pelatihan->count()]);

        return view('validasi.show_pelatihan_ajax', [
            'pelatihan' => $pelatihan,
            'peserta_pelatihan' => $peserta_pelatihan
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
                    
                    $pelatihan->peserta_pelatihan()
                        ->where('status', 'Pending')
                        ->update([
                            'status' => $request->status,
                            'updated_at' => now()
                        ]);

                    if ($request->status === 'Approved') {
                        $peserta = $pelatihan->peserta_pelatihan()
                            ->with('dosen.user')
                            ->where('status', 'Approved')
                            ->get();

                        foreach ($peserta as $p) {
                            NotifikasiModel::create([
                                'user_id' => $p->dosen->user->user_id,
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
                    
                    $sertifikasi->peserta_sertifikasi()
                        ->where('status', 'Pending')
                        ->update([
                            'status' => $request->status,
                            'updated_at' => now()
                        ]);

                    if ($request->status === 'Approved') {
                        $peserta = $sertifikasi->peserta_sertifikasi()
                            ->with('dosen.user')
                            ->where('status', 'Approved')
                            ->get();

                        foreach ($peserta as $p) {
                            NotifikasiModel::create([
                                'user_id' => $p->dosen->user->user_id,
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
