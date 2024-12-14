<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratModel;
use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\PesertaModel;
use App\Models\PesertaSertifikasiModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

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

        return view('surat_tugas.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request) 
{ 
    try {
        // Query untuk pelatihan yang dosen terdaftar sebagai peserta
        $pelatihan = DB::table('peserta_pelatihan as pp')
            ->join('m_pelatihan as p', 'pp.pelatihan_id', '=', 'p.pelatihan_id')
            ->join('m_user as u', 'pp.user_id', '=', 'u.user_id')
            ->leftJoin('surat_tugas as st', function($join) {
                $join->on('pp.pelatihan_id', '=', 'st.peserta_pelatihan_id')
                    ->where('st.user_id', '=', auth()->id()); // Tambahkan kondisi user_id
            })
            ->where('pp.user_id', auth()->id()) // Hanya untuk dosen yang login
            ->where('pp.status', 'Approved')
            ->select(
                'p.pelatihan_id as id',
                'p.nama_pelatihan as nama_kegiatan',
                DB::raw("'pelatihan' as jenis_kegiatan"),
                'st.surat_tugas_id', // Tambahkan ini
                DB::raw('CASE WHEN st.surat_tugas_id IS NOT NULL THEN 1 ELSE 0 END as has_surat')
            )
            ->distinct();

        // Query untuk sertifikasi yang dosen terdaftar sebagai peserta
        $sertifikasi = DB::table('peserta_sertifikasi as ps')
            ->join('m_sertifikasi as s', 'ps.sertifikasi_id', '=', 's.sertifikasi_id')
            ->join('m_user as u', 'ps.user_id', '=', 'u.user_id')
            ->leftJoin('surat_tugas as st', function($join) {
                $join->on('ps.sertifikasi_id', '=', 'st.peserta_sertifikasi_id')
                    ->where('st.user_id', '=', auth()->id()); // Tambahkan kondisi user_id
            })
            ->where('ps.user_id', auth()->id()) // Hanya untuk dosen yang login
            ->where('ps.status', 'Approved')
            ->select(
                's.sertifikasi_id as id',
                's.nama_sertifikasi as nama_kegiatan',
                DB::raw("'sertifikasi' as jenis_kegiatan"),
                'st.surat_tugas_id', // Tambahkan ini
                DB::raw('CASE WHEN st.surat_tugas_id IS NOT NULL THEN 1 ELSE 0 END as has_surat')
            )
            ->distinct();

        // Filter berdasarkan jenis kegiatan
        if ($request->jenis_kegiatan) {
            $query = $request->jenis_kegiatan === 'pelatihan' ? $pelatihan : $sertifikasi;
        } else {
            $query = $pelatihan->union($sertifikasi);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $buttons = '<div class="btn-group">';
                $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/' . $row->id . '/show_' . $row->jenis_kegiatan . '_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                
                if ($row->has_surat && $row->surat_tugas_id) {
                    // Gunakan <a> tag dengan href langsung ke download
                    $buttons .= '<a href="' . url('/surat_tugas/download/' . $row->surat_tugas_id) . '" target="_blank" class="btn btn-warning btn-sm ml-1">Download</a>';
                } else {
                    $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/'.$row->id.'/validasi?jenis='.$row->jenis_kegiatan) . '\')" class="btn btn-primary btn-sm ml-1">Buat Surat</button>';
                }
                
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['aksi'])
            ->make(true);

    } catch (\Exception $e) {
        Log::error('Error in list method: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

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

    public function validasi(Request $request)
{
    $request->validate([
        'kegiatan_id' => 'required',
        'status' => 'required|in:Approved,Rejected',
    ]);

    try {
        DB::beginTransaction();

        if ($request->kegiatan === 'pelatihan') {
            PesertaModel::where('pelatihan_id', $request->kegiatan_id)
                ->update(['status' => $request->status]);
        } else {
            PesertaSertifikasiModel::where('sertifikasi_id', $request->kegiatan_id)
                ->update(['status' => $request->status]);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Pengajuan berhasil divalidasi'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Tambahkan logging untuk melihat data yang diterima
            Log::info('Data yang diterima:', $request->all());
            
            // Buat surat tugas baru
            $surat = new SuratModel();
            $surat->user_id = auth()->id();
            
            // Ambil data peserta berdasarkan jenis kegiatan
            if ($request->jenis === 'sertifikasi') {
                $peserta = PesertaSertifikasiModel::where('sertifikasi_id', $request->kegiatan_id)
                    ->where('user_id', auth()->id())
                    ->first();
                    
                if (!$peserta) {
                    throw new \Exception('Data peserta sertifikasi tidak ditemukan');
                }
                
                $surat->peserta_sertifikasi_id = $peserta->peserta_sertifikasi_id;
            } else {
                $peserta = PesertaModel::where('pelatihan_id', $request->kegiatan_id)
                    ->where('user_id', auth()->id())
                    ->first();
                    
                if (!$peserta) {
                    throw new \Exception('Data peserta pelatihan tidak ditemukan');
                }
                
                $surat->peserta_pelatihan_id = $peserta->peserta_pelatihan_id;
            }
            
            $surat->save();
            
            // Log ID surat yang dibuat
            Log::info('Surat berhasil dibuat dengan ID:', ['surat_id' => $surat->surat_tugas_id]);
            
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Surat tugas berhasil dibuat',
                'surat_id' => $surat->surat_tugas_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store surat tugas: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        try {
            Log::info('Mencoba download surat ID: ' . $id);

            // Cek apakah view ada
            if (!view()->exists('surat_tugas.dwonload')) {
                Log::error('View tidak ditemukan: surat_tugas.dwonload');
                throw new \Exception('Template surat tidak ditemukan');
            }
            Log::info('View ditemukan');

            $surat = SuratModel::where('surat_tugas_id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            Log::info('Data surat:', $surat->toArray());

            // Ambil data kegiatan
            $kegiatan = null;
            $nama_kegiatan = '';

            if ($surat->peserta_sertifikasi_id) {
                $peserta = PesertaSertifikasiModel::with('sertifikasi')->find($surat->peserta_sertifikasi_id);
                $kegiatan = $peserta->sertifikasi;
                $nama_kegiatan = $kegiatan->nama_sertifikasi;
            } elseif ($surat->peserta_pelatihan_id) {
                $peserta = PesertaModel::with('pelatihan')->find($surat->peserta_pelatihan_id);
                $kegiatan = $peserta->pelatihan;
                $nama_kegiatan = $kegiatan->nama_pelatihan;
            }
            
            Log::info('Data kegiatan:', [
                'nama_kegiatan' => $nama_kegiatan,
                'kegiatan' => $kegiatan
            ]);

            $data = [
                'surat' => $surat,
                'kegiatan' => $kegiatan,
                'nama_kegiatan' => $nama_kegiatan, // Teruskan variabel $nama_kegiatan ke view
                'nama' => auth()->user()->nama,
                'nip' => auth()->user()->nip,
                'jabatan' => 'Teknologi Informasi',
                'nomor_surat' => 'XX/XX/XX/'.date('Y'),
                'tgl_surat' => date('d F Y')
            ];
            Log::info('Data untuk PDF:', $data);
            
            // Coba render view dulu untuk debugging
            $view = view('surat_tugas.dwonload', $data)->render();
            Log::info('View berhasil di-render');

            $pdf = PDF::loadView('surat_tugas.dwonload', $data);
            $pdf->setPaper('a4', 'portrait');
            
            return $pdf->stream('surat_tugas_'.$id.'.pdf');

        } catch (\Exception $e) {
            Log::error('Error download surat: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengunduh surat: ' . $e->getMessage()
            ], 404);
        }
    }
}