<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratModel;
use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\PesertaPelatihanModel;
use App\Models\NotifikasiModel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Hash;
use App\Models\PesertaSertifikasiModel;
use Illuminate\Support\Facades\Validator;
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
            $user = auth()->user();
            $isAdmin = $user->level_id == 1;
            $isDosen = $user->level_id == 3;

            // Query untuk pelatihan
            $pelatihan = DB::table('peserta_pelatihan as pp')
                ->join('m_pelatihan as p', 'pp.pelatihan_id', '=', 'p.pelatihan_id')
                ->join('m_user as u', 'pp.user_id', '=', 'u.user_id')
                ->leftJoin('surat_tugas as st', function($join) {
                    $join->on('pp.peserta_pelatihan_id', '=', 'st.peserta_pelatihan_id');
                })
                ->where('pp.status', 'Approved')
                ->select(
                    'p.pelatihan_id as id',
                    'p.nama_pelatihan as nama_kegiatan',
                    'pp.status',
                    'pp.peserta_pelatihan_id',
                    DB::raw("'pelatihan' as jenis_kegiatan"),
                    'st.surat_tugas_id',
                    DB::raw('CASE WHEN st.surat_tugas_id IS NOT NULL THEN 1 ELSE 0 END as has_surat')
                );

            // Query untuk sertifikasi
            $sertifikasi = DB::table('peserta_sertifikasi as ps')
                ->join('m_sertifikasi as s', 'ps.sertifikasi_id', '=', 's.sertifikasi_id')
                ->join('m_user as u', 'ps.user_id', '=', 'u.user_id')
                ->leftJoin('surat_tugas as st', function($join) {
                    $join->on('ps.peserta_sertifikasi_id', '=', 'st.peserta_sertifikasi_id');
                })
                ->where('ps.status', 'Approved')
                ->select(
                    's.sertifikasi_id as id',
                    's.nama_sertifikasi as nama_kegiatan',
                    'ps.status',
                    'ps.peserta_sertifikasi_id',
                    DB::raw("'sertifikasi' as jenis_kegiatan"),
                    'st.surat_tugas_id',
                    DB::raw('CASE WHEN st.surat_tugas_id IS NOT NULL THEN 1 ELSE 0 END as has_surat')
                );

            if ($isDosen) {
                $userId = $user->user_id;
                $pelatihan->where('pp.user_id', $userId);
                $sertifikasi->where('ps.user_id', $userId);
            }

            $query = $request->jenis_kegiatan ? 
                ($request->jenis_kegiatan === 'pelatihan' ? $pelatihan : $sertifikasi) :
                $pelatihan->union($sertifikasi);

                return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('aksi', function($row) use ($isDosen, $isAdmin) {
                    $buttons = '<div class="btn-group">';
                    
                    if ($isDosen) {
                        // Tombol untuk Dosen
                        $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/' . $row->id . '/show_' . $row->jenis_kegiatan . '_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                        
                        // Tombol Download - abu-abu jika belum ada surat
                        $buttonClass = $row->has_surat ? 'btn-warning' : 'btn-secondary';
                        $disabled = !$row->has_surat ? 'disabled' : '';
                        $buttons .= '<a href="' . url('/surat_tugas/export_pdf/' . $row->surat_tugas_id) . '" class="btn ' . $buttonClass . ' btn-sm ml-1" ' . $disabled . '>Download</a>';
                    }

                    if ($isAdmin) {
                        $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/' . $row->id . '/show_' . $row->jenis_kegiatan . '_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                        $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/upload/' . $row->id . '?jenis=' . $row->jenis_kegiatan) . '\')" class="btn btn-success btn-sm ml-1">Upload</button>';
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
    public function show_sertifikasi_ajax($id)
    {
        try {
            $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
            
            // Cek jika user adalah admin atau dosen
            if (auth()->user()->level_id == 1) { // Admin
                $peserta_sertifikasi = PesertaSertifikasiModel::with(['user']) // Pastikan relasi user dimuat
                    ->where('sertifikasi_id', $id)
                    ->where('status', 'Approved')
                    ->get();
            } else { // Dosen
                $peserta_sertifikasi = PesertaSertifikasiModel::with(['user']) // Pastikan relasi user dimuat
                    ->where('sertifikasi_id', $id)
                    ->where('user_id', auth()->user()->user_id)
                    ->first();
            }

            return view('surat_tugas.show_sertifikasi_ajax', [
                'sertifikasi' => $sertifikasi,
                'peserta' => $peserta_sertifikasi
            ]);

        } catch (\Exception $e) {
            Log::error('Error show sertifikasi: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show_pelatihan_ajax($id)
    {
        try {
            $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
            
            // Cek jika user adalah admin atau dosen
            if (auth()->user()->level_id == 1) { // Admin
                $peserta_pelatihan = PesertaPelatihanModel::with(['user']) // Pastikan relasi user dimuat
                    ->where('pelatihan_id', $id)
                    ->where('status', 'Approved')
                    ->get();
            } else { // Dosen
                $peserta_pelatihan = PesertaPelatihanModel::with(['user']) // Pastikan relasi user dimuat
                    ->where('pelatihan_id', $id)
                    ->where('user_id', auth()->user()->user_id)
                    ->first();
            }

            return view('surat_tugas.show_pelatihan_ajax', [
                'pelatihan' => $pelatihan,
                'peserta' => $peserta_pelatihan
            ]);

        } catch (\Exception $e) {
            Log::error('Error show pelatihan: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function upload($id)
    {
        try {
            if (request('jenis') == 'pelatihan') {
                $data = PelatihanModel::findOrFail($id);
                $nama_kegiatan = $data->nama_pelatihan;
            } else {
                $data = SertifikasiModel::findOrFail($id);
                $nama_kegiatan = $data->nama_sertifikasi;
            }
    
            return view('surat_tugas.upload', [
                'kegiatan_id' => $id,
                'jenis' => request('jenis'),
                'nama_kegiatan' => $nama_kegiatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function upload_ajax($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file_surat' => 'required|mimes:pdf,doc,docx|max:2048',
                'kegiatan_id' => 'required',
                'jenis' => 'required|in:pelatihan,sertifikasi'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ]);
            }
    
            DB::beginTransaction();
    
            // Buat direktori jika belum ada
            $uploadPath = public_path('uploads/surat');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            // Simpan file
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $fileName);
    
            // Simpan ke database
            if ($request->jenis == 'pelatihan') {
                $peserta = PesertaPelatihanModel::where('pelatihan_id', $request->kegiatan_id)
                    ->where('status', 'Approved')
                    ->first();
    
                if (!$peserta) {
                    throw new \Exception('Data peserta pelatihan tidak ditemukan');
                }
    
                $surat = new SuratModel;
                $surat->peserta_pelatihan_id = $peserta->peserta_pelatihan_id;
                $surat->user_id = $peserta->user_id;
                $surat->file_surat = $fileName;
                $surat->save();
    
            } else {
                $peserta = PesertaSertifikasiModel::where('sertifikasi_id', $request->kegiatan_id)
                    ->where('status', 'Approved')
                    ->first();
    
                if (!$peserta) {
                    throw new \Exception('Data peserta sertifikasi tidak ditemukan');
                }
    
                $surat = new SuratModel;
                $surat->peserta_sertifikasi_id = $peserta->peserta_sertifikasi_id;
                $surat->user_id = $peserta->user_id;
                $surat->file_surat = $fileName;
                $surat->save();
            }
    
            // Kirim notifikasi ke peserta
            // NotifikasiModel::create([
            //     'user_id' => $peserta->user_id,
            //     'title' => 'Surat Tugas',
            //     'message' => 'Surat tugas telah diunggah untuk kegiatan ' . 
            //                 ($request->jenis == 'pelatihan' ? 'pelatihan' : 'sertifikasi'),
            //     'type' => 'surat_tugas'
            // ]);
    
            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => 'Surat berhasil diupload'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Modifikasi fungsi export_template yang sudah ada
    public function export_template(Request $request)
    {
        try {
            // Definisikan path ke file template
            $templatePath = public_path('template/Surat_Tugas.docx');
            
            // Cek apakah file ada
            if (!file_exists($templatePath)) {
                throw new \Exception('Template surat tidak ditemukan');
            }

            // Return file untuk didownload
            return response()->download($templatePath, 'Surat_Tugas_Template.docx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]);

        } catch (\Exception $e) {
            Log::error('Error download template: ' . $e->getMessage());
            return back()->with('error', 'Gagal mendownload template: ' . $e->getMessage());
        }
    }

    // View untuk menampilkan form upload dan download template
    public function export_pdf($id)
{
    try {
        Log::info('Attempting to download file with ID: ' . $id);
        
        $surat = SuratModel::findOrFail($id);
        Log::info('Surat found:', $surat->toArray());
        
        $path = public_path('uploads/surat/' . $surat->file_surat);
        Log::info('File path: ' . $path);
        
        if (!file_exists($path)) {
            Log::error('File not found at path: ' . $path);
            throw new \Exception('File tidak ditemukan di lokasi: ' . $path);
        }

        return response()->download($path);

    } catch (\Exception $e) {
        Log::error('Error downloading file: ' . $e->getMessage());
        return back()->with('error', 'Gagal mendownload surat: ' . $e->getMessage());
    }
}

    // public function export_template(Request $request)
    // {
    //     try {
    //         // Tambahkan activeMenu dan breadcrumb
    //         $activeMenu = 'surat_tugas';
            
    //         $breadcrumb = (object) [
    //             'title' => 'Export Template Surat Tugas',
    //             'list' => ['Home', 'Surat Tugas', 'Export Template']
    //         ];

            
    //         if ($request->jenis == 'pelatihan') {
    //             $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode']);
    //             $peserta = PesertaPelatihanModel::with(['dosen.user'])
    //                 ->where('pelatihan_id')
    //                 ->where('status', 'Approved')
    //                 ->first();

    //             $data = [
    //                 'nama_kegiatan' => $pelatihan->nama_pelatihan,
    //                 'kegiatan' => $pelatihan,
    //                 'tanggal' => $pelatihan->tanggal,
    //                 'lokasi' => $pelatihan->lokasi,
    //                 'nama' => $peserta->dosen->user->nama ?? '',
    //                 'nip' => $peserta->dosen->user->nip ?? '',
    //                 'tgl_surat' => date('Y-m-d'),
    //                 'nomor_surat' => '..../..../..../.....', 
    //                 'jenis' => 'pelatihan',
    //                 'activeMenu' => $activeMenu,
    //                 'breadcrumb' => $breadcrumb  // Tambahkan ini
    //             ];
    //         } else {
    //             $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode']);
    //             $peserta = PesertaSertifikasiModel::with(['dosen.user'])
    //                 ->where('sertifikasi_id')
    //                 ->where('status', 'Approved')
    //                 ->first();

    //             $data = [
    //                 'nama_kegiatan' => $sertifikasi->nama_sertifikasi,
    //                 'kegiatan' => $sertifikasi,
    //                 'tanggal' => $sertifikasi->tanggal,
    //                 'lokasi' => $sertifikasi->lokasi,
    //                 'nama' => $peserta->dosen->user->nama ?? '',
    //                 'nip' => $peserta->dosen->user->nip ?? '',
    //                 'tgl_surat' => date('Y-m-d'),
    //                 'nomor_surat' => '..../..../..../.....', 
    //                 'jenis' => 'sertifikasi',
    //                 'activeMenu' => $activeMenu,
    //                 'breadcrumb' => $breadcrumb  // Tambahkan ini
    //             ];
    //         }

    //         $pdf = PDF::loadView('surat_tugas.format_surat', $data);
    //         $pdf->setPaper('a4', 'portrait');
    //         return $pdf->stream('Format_Surat_Tugas.pdf');

    //     } catch (\Exception $e) {
    //         Log::error('Error export template: ' . $e->getMessage());
    //         return back()->with('error', 'Gagal mengexport template: ' . $e->getMessage());
    //     }
    // }

    
    // Tambahkan fungsi untuk upload template
    // public function upload_template(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'file_template' => 'required|mimes:docx|max:2048',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi gagal',
    //                 'errors' => $validator->errors()
    //             ]);
    //         }

    //         // Simpan file
    //         $file = $request->file('file_template');
    //         $fileName = 'surat_tugas_template.docx';
    //         $file->move(public_path('template'), $fileName);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Template berhasil diupload'
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    //     public function store(Request $request)
    //     {
    //         try {
    //             DB::beginTransaction();
                
    //             // Tambahkan logging untuk melihat data yang diterima
    //             Log::info('Data yang diterima:', $request->all());
                
    //             // Buat surat tugas baru
    //             $surat = new SuratModel();
    //             $surat->user_id = auth()->id();
                
    //             // Ambil data peserta berdasarkan jenis kegiatan
    //             if ($request->jenis === 'sertifikasi') {
    //                 $peserta = PesertaSertifikasiModel::where('sertifikasi_id', $request->kegiatan_id)
    //                     ->where('user_id', auth()->id())
    //                     ->first();
                        
    //                 if (!$peserta) {
    //                     throw new \Exception('Data peserta sertifikasi tidak ditemukan');
    //                 }
                    
    //                 $surat->peserta_sertifikasi_id = $peserta->peserta_sertifikasi_id;
    //             } else {
    //                 $peserta = PesertaModel::where('pelatihan_id', $request->kegiatan_id)
    //                     ->where('user_id', auth()->id())
    //                     ->first();
                        
    //                 if (!$peserta) {
    //                     throw new \Exception('Data peserta pelatihan tidak ditemukan');
    //                 }
                    
    //                 $surat->peserta_pelatihan_id = $peserta->peserta_pelatihan_id;
    //             }
                
    //             $surat->save();
                
    //             // Log ID surat yang dibuat
    //             Log::info('Surat berhasil dibuat dengan ID:', ['surat_id' => $surat->surat_tugas_id]);
                
    //             DB::commit();
                
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Surat tugas berhasil dibuat',
    //                 'surat_id' => $surat->surat_tugas_id
    //             ]);

    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             Log::error('Error store surat tugas: ' . $e->getMessage());
    //             Log::error('Stack trace: ' . $e->getTraceAsString());
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //             ], 500);
    //         }
    //     }

    //     public function download($id)
    //     {
    //         try {
    //             Log::info('Mencoba download surat ID: ' . $id);

    //             // Cek apakah view ada
    //             if (!view()->exists('surat_tugas.dwonload')) {
    //                 Log::error('View tidak ditemukan: surat_tugas.dwonload');
    //                 throw new \Exception('Template surat tidak ditemukan');
    //             }
    //             Log::info('View ditemukan');

    //             $surat = SuratModel::where('surat_tugas_id', $id)
    //                 ->where('user_id', auth()->id())
    //                 ->firstOrFail();
    //             Log::info('Data surat:', $surat->toArray());

    //             // Ambil data kegiatan
    //             $kegiatan = null;
    //             $nama_kegiatan = '';

    //             if ($surat->peserta_sertifikasi_id) {
    //                 $peserta = PesertaSertifikasiModel::with('sertifikasi')->find($surat->peserta_sertifikasi_id);
    //                 $kegiatan = $peserta->sertifikasi;
    //                 $nama_kegiatan = $kegiatan->nama_sertifikasi;
    //             } elseif ($surat->peserta_pelatihan_id) {
    //                 $peserta = PesertaModel::with('pelatihan')->find($surat->peserta_pelatihan_id);
    //                 $kegiatan = $peserta->pelatihan;
    //                 $nama_kegiatan = $kegiatan->nama_pelatihan;
    //             }
                
    //             Log::info('Data kegiatan:', [
    //                 'nama_kegiatan' => $nama_kegiatan,
    //                 'kegiatan' => $kegiatan
    //             ]);

    //             $data = [
    //                 'surat' => $surat,
    //                 'kegiatan' => $kegiatan,
    //                 'nama_kegiatan' => $nama_kegiatan, // Teruskan variabel $nama_kegiatan ke view
    //                 'nama' => auth()->user()->nama,
    //                 'nip' => auth()->user()->nip,
    //                 'jabatan' => 'Teknologi Informasi',
    //                 'nomor_surat' => 'XX/XX/XX/'.date('Y'),
    //                 'tgl_surat' => date('d F Y')
    //             ];
    //             Log::info('Data untuk PDF:', $data);
                
    //             // Coba render view dulu untuk debugging
    //             $view = view('surat_tugas.dwonload', $data)->render();
    //             Log::info('View berhasil di-render');

    //             $pdf = PDF::loadView('surat_tugas.dwonload', $data);
    //             $pdf->setPaper('a4', 'portrait');
                
    //             return $pdf->stream('surat_tugas_'.$id.'.pdf');

    //         } catch (\Exception $e) {
    //             Log::error('Error download surat: ' . $e->getMessage());
    //             Log::error('Stack trace: ' . $e->getTraceAsString());
                
    //             return response()->json([
    //                 'error' => true,
    //                 'message' => 'Gagal mengunduh surat: ' . $e->getMessage()
    //             ], 404);
    //         }
    //     }
}