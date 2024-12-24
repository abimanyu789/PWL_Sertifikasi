<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratModel;
use App\Models\JenisModel;
use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\PesertaPelatihanModel;
use App\Models\UploadPelatihanModel;
use App\Models\UploadSertifikasiModel;
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
use Illuminate\Support\Facades\Auth;

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

//     public function list(Request $request) 
// { 
//     try {
//         $user = auth()->user();
//         $isAdmin = $user->level_id == 1;
//         $isDosen = $user->level_id == 3;

//         // Query untuk pelatihan
//         $pelatihan = DB::table('peserta_pelatihan as pp')
//             ->join('m_pelatihan as p', 'pp.pelatihan_id', '=', 'p.pelatihan_id')
//             ->join('m_user as u', 'pp.user_id', '=', 'u.user_id')
//             ->leftJoin('surat_tugas as st', function($join) {
//                 $join->on('pp.peserta_pelatihan_id', '=', 'st.peserta_pelatihan_id')
//                 ->whereNotNull('st.file_surat');
//             })
//             ->whereIn('pp.status', ['Approved', 'Rejected']) // Ubah ini untuk menerima status Approved dan Rejected
//             ->select(
//                 'p.pelatihan_id as id',
//                 'p.nama_pelatihan as nama_kegiatan',
//                 'pp.status', // Tambahkan status ke select
//                 'pp.peserta_pelatihan_id',
//                 DB::raw("'pelatihan' as jenis_kegiatan"),
//                 'st.surat_tugas_id',
//                 DB::raw('CASE WHEN st.file_surat IS NOT NULL THEN 1 ELSE 0 END as has_surat')
//             );

//         // Query untuk sertifikasi
//         $sertifikasi = DB::table('peserta_sertifikasi as ps')
//             ->join('m_sertifikasi as s', 'ps.sertifikasi_id', '=', 's.sertifikasi_id')
//             ->join('m_user as u', 'ps.user_id', '=', 'u.user_id')
//             ->leftJoin('surat_tugas as st', function($join) {
//                 $join->on('ps.peserta_sertifikasi_id', '=', 'st.peserta_sertifikasi_id')
//                 ->whereNotNull('st.file_surat');
//             })
//             ->whereIn('ps.status', ['Approved', 'Rejected']) // Ubah ini untuk menerima status Approved dan Rejected
//             ->select(
//                 's.sertifikasi_id as id',
//                 's.nama_sertifikasi as nama_kegiatan',
//                 'ps.status', // Tambahkan status ke select
//                 'ps.peserta_sertifikasi_id',
//                 DB::raw("'sertifikasi' as jenis_kegiatan"),
//                 'st.surat_tugas_id',
//                 DB::raw('CASE WHEN st.file_surat IS NOT NULL THEN 1 ELSE 0 END as has_surat')
//             );

//         if ($isDosen) {
//             $userId = $user->user_id;
//             $pelatihan->where('pp.user_id', $userId);
//             $sertifikasi->where('ps.user_id', $userId);
//         }

//         $query = $request->jenis_kegiatan ? 
//             ($request->jenis_kegiatan === 'pelatihan' ? $pelatihan : $sertifikasi) :
//             $pelatihan->union($sertifikasi);

//         return DataTables::of($query)
//             ->addIndexColumn()
//             ->addColumn('aksi', function($row) use ($isDosen, $isAdmin) {
//                 $buttons = '<div class="btn-group">';
                
//                 if ($isDosen) {
//                     $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/' . $row->id . '/show_' . $row->jenis_kegiatan . '_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    
//                     $downloadClass = $row->has_surat ? 'btn-warning' : 'btn-secondary';
//                     $uploadClass = ($row->status === 'Approved') ? 'btn-primary' : 'btn-secondary';
//                     $downloadDisabled = !$row->has_surat ? 'disabled' : '';
//                     $uploadDisabled = ($row->status !== 'Approved') ? 'disabled' : '';
                    
//                     $buttons .= '<a href="' . url('/surat_tugas/export_pdf/' . $row->surat_tugas_id) . '" class="btn ' . $downloadClass . ' btn-sm ml-1" ' . $downloadDisabled . '>Download</a>';
//                     $buttons .= '<a href="' . url('/surat_tugas/upload_sertif/' . $row->surat_tugas_id) . '" class="btn ' . $uploadClass . ' btn-sm ml-1" ' . $uploadDisabled . '>Upload</a>';
//                 }

//                 if ($isAdmin) {
//                     $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/' . $row->id . '/show_' . $row->jenis_kegiatan . '_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    
//                     // Tampilkan tombol upload hanya jika status Approved
//                     if ($row->status === 'Approved') {
//                         $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/upload/' . $row->id . '?jenis=' . $row->jenis_kegiatan) . '\')" class="btn btn-success btn-sm ml-1">Upload</button>';
//                     }
//                 }
                
//                 $buttons .= '</div>';
//                 return $buttons;
//             })
//             ->addColumn('status_validasi', function($row) {
//                 return $row->status;
//             })
//             ->rawColumns(['aksi'])
//             ->make(true);

//     } catch (\Exception $e) {
//         Log::error('Error in list method: ' . $e->getMessage());
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }

public function list(Request $request) 
{ 
    try {
        $user = auth()->user();
        $isAdmin = $user->level_id == 1;
        $isDosen = $user->level_id == 3;

        // Query untuk pelatihan
        $pelatihan = DB::table('m_pelatihan as p')
            ->join('peserta_pelatihan as pp', 'p.pelatihan_id', '=', 'pp.pelatihan_id')
            ->join('m_user as u', 'pp.user_id', '=', 'u.user_id')
            ->leftJoin('surat_tugas as st', function($join) {
                $join->on('pp.peserta_pelatihan_id', '=', 'st.peserta_pelatihan_id');
            })
            ->whereIn('pp.status', ['Approved', 'Rejected'])
            ->select(
                'p.pelatihan_id as id',
                'p.nama_pelatihan as nama_kegiatan',
                DB::raw('MAX(pp.status) as status'),
                DB::raw('MIN(pp.peserta_pelatihan_id) as peserta_pelatihan_id'),
                DB::raw("'pelatihan' as jenis_kegiatan"),
                DB::raw('MIN(st.surat_tugas_id) as surat_tugas_id'),
                DB::raw('CASE WHEN MIN(st.surat_tugas_id) IS NOT NULL THEN 1 ELSE 0 END as has_surat')
            )
            ->groupBy('p.pelatihan_id', 'p.nama_pelatihan');

        // Query untuk sertifikasi
        $sertifikasi = DB::table('m_sertifikasi as s')
            ->join('peserta_sertifikasi as ps', 's.sertifikasi_id', '=', 'ps.sertifikasi_id')
            ->join('m_user as u', 'ps.user_id', '=', 'u.user_id')
            ->leftJoin('surat_tugas as st', function($join) {
                $join->on('ps.peserta_sertifikasi_id', '=', 'st.peserta_sertifikasi_id');
            })
            ->whereIn('ps.status', ['Approved', 'Rejected'])
            ->select(
                's.sertifikasi_id as id',
                's.nama_sertifikasi as nama_kegiatan',
                DB::raw('MAX(ps.status) as status'),
                DB::raw('MIN(ps.peserta_sertifikasi_id) as peserta_sertifikasi_id'),
                DB::raw("'sertifikasi' as jenis_kegiatan"),
                DB::raw('MIN(st.surat_tugas_id) as surat_tugas_id'),
                DB::raw('CASE WHEN MIN(st.surat_tugas_id) IS NOT NULL THEN 1 ELSE 0 END as has_surat')
            )
            ->groupBy('s.sertifikasi_id', 's.nama_sertifikasi');

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
                    $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/' . $row->id . '/show_' . $row->jenis_kegiatan . '_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    
                    // Tambahkan tombol upload bukti jika status Approved
                    if ($row->status === 'Approved') {
                        $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/upload_bukti/' . $row->id . '/' . $row->jenis_kegiatan) . '\')" class="btn btn-success btn-sm ml-1">Upload Bukti</button>';
                    }
                    
                    $downloadClass = $row->has_surat ? 'btn-warning' : 'btn-secondary';
                    $downloadDisabled = !$row->has_surat ? 'disabled' : '';
                    
                    $buttons .= '<a href="' . url('/surat_tugas/export_pdf/' . $row->surat_tugas_id) . '" class="btn ' . $downloadClass . ' btn-sm ml-1" ' . $downloadDisabled . '>Download</a>';
                }

                if ($isAdmin) {
                    $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/' . $row->id . '/show_' . $row->jenis_kegiatan . '_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    
                    // Tampilkan tombol upload hanya jika status Approved
                    if ($row->status === 'Approved') {
                        $buttons .= '<button onclick="modalAction(\'' . url('/surat_tugas/upload/' . $row->id . '?jenis=' . $row->jenis_kegiatan) . '\')" class="btn btn-success btn-sm ml-1">Upload</button>';
                    }
                }
                
                $buttons .= '</div>';
                return $buttons;
            })
            ->addColumn('status_validasi', function($row) {
                if ($row->status === 'Approved') {
                    return '<span class="badge badge-success">Approved</span>';
                } else if ($row->status === 'Rejected') {
                    return '<span class="badge badge-danger">Rejected</span>';
                }
                return '<span class="badge badge-warning">Pending</span>';
            })
            ->rawColumns(['aksi', 'status_validasi'])
            ->make(true);

    } catch (\Exception $e) {
        Log::error('Error in list method: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

//     public function show_sertifikasi_ajax($id)
// {
//     try {
//         $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
        
//         // Cek jika user adalah admin atau dosen
//         if (auth()->user()->level_id == 1) { // Admin
//             $peserta_sertifikasi = PesertaSertifikasiModel::with('user')
//                 ->where('sertifikasi_id', $id)
//                 ->whereIn('status', ['Approved', 'Rejected']) // Tambahkan ini untuk menampilkan yang approved dan rejected
//                 ->get();
//         } else { // Dosen
//             $peserta_sertifikasi = PesertaSertifikasiModel::with('user')
//                 ->where('sertifikasi_id', $id)
//                 ->where('user_id', auth()->user()->user_id)
//                 ->first();
//         }

//         return view('surat_tugas.show_sertifikasi_ajax', [
//             'sertifikasi' => $sertifikasi,
//             'peserta' => $peserta_sertifikasi
//         ]);

//     } catch (\Exception $e) {
//         Log::error('Error show sertifikasi: ' . $e->getMessage());
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }

public function show_sertifikasi_ajax($id)
{
    try {
        $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
        
        // Get all participants regardless of user level
        $peserta_sertifikasi = PesertaSertifikasiModel::with(['user'])
            ->where('sertifikasi_id', $id)
            ->where('status', 'Approved')
            ->get();

        return view('surat_tugas.show_sertifikasi_ajax', [
            'sertifikasi' => $sertifikasi,
            'peserta' => $peserta_sertifikasi
        ]);

    } catch (\Exception $e) {
        Log::error('Error show sertifikasi: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    // public function show_pelatihan_ajax($id)
    // {
    //     try {
    //         $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
            
    //         // Cek jika user adalah admin atau dosen
    //         if (auth()->user()->level_id == 1) { // Admin
    //             $peserta_pelatihan = PesertaPelatihanModel::with(['user']) // Pastikan relasi user dimuat
    //                 ->where('pelatihan_id', $id)
    //                 ->where('status', 'Approved')
    //                 ->get();
    //         } else { // Dosen
    //             $peserta_pelatihan = PesertaPelatihanModel::with(['user']) // Pastikan relasi user dimuat
    //                 ->where('pelatihan_id', $id)
    //                 ->where('user_id', auth()->user()->user_id)
    //                 ->first();
    //         }

    //         return view('surat_tugas.show_pelatihan_ajax', [
    //             'pelatihan' => $pelatihan,
    //             'peserta' => $peserta_pelatihan
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Error show pelatihan: ' . $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
public function show_pelatihan_ajax($id)
{
    try {
        $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
        
        // Get all participants regardless of user level
        $peserta_pelatihan = PesertaPelatihanModel::with(['user'])
            ->where('pelatihan_id', $id)
            ->where('status', 'Approved')
            ->get();

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

    // public function upload_ajax($id, Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'file_surat' => 'required|mimes:pdf,doc,docx|max:2048',
    //             'kegiatan_id' => 'required',
    //             'jenis' => 'required|in:pelatihan,sertifikasi'
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi gagal',
    //                 'errors' => $validator->errors()
    //             ]);
    //         }
    
    //         DB::beginTransaction();
    
    //         // Buat direktori jika belum ada
    //         $uploadPath = public_path('uploads/surat');
    //         if (!file_exists($uploadPath)) {
    //             mkdir($uploadPath, 0777, true);
    //         }
    
    //         // Simpan file
    //         $file = $request->file('file_surat');
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $file->move($uploadPath, $fileName);
    
    //         // Simpan ke database
    //         if ($request->jenis == 'pelatihan') {
    //             $peserta = PesertaPelatihanModel::where('pelatihan_id', $request->kegiatan_id)
    //                 ->where('status', 'Approved')
    //                 ->first();
    
    //             if (!$peserta) {
    //                 throw new \Exception('Data peserta pelatihan tidak ditemukan');
    //             }
    
    //             $surat = new SuratModel;
    //             $surat->peserta_pelatihan_id = $peserta->peserta_pelatihan_id;
    //             $surat->user_id = $peserta->user_id;
    //             $surat->file_surat = $fileName;
    //             $surat->save();
    
    //         } else {
    //             $peserta = PesertaSertifikasiModel::where('sertifikasi_id', $request->kegiatan_id)
    //                 ->where('status', 'Approved')
    //                 ->first();
    
    //             if (!$peserta) {
    //                 throw new \Exception('Data peserta sertifikasi tidak ditemukan');
    //             }
    
    //             $surat = new SuratModel;
    //             $surat->peserta_sertifikasi_id = $peserta->peserta_sertifikasi_id;
    //             $surat->user_id = $peserta->user_id;
    //             $surat->file_surat = $fileName;
    //             $surat->save();
    //         }
    
    //         // Kirim notifikasi ke peserta
    //         // NotifikasiModel::create([
    //         //     'user_id' => $peserta->user_id,
    //         //     'title' => 'Surat Tugas',
    //         //     'message' => 'Surat tugas telah diunggah untuk kegiatan ' . 
    //         //                 ($request->jenis == 'pelatihan' ? 'pelatihan' : 'sertifikasi'),
    //         //     'type' => 'surat_tugas'
    //         // ]);
    
    //         DB::commit();
    
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Surat berhasil diupload'
    //         ]);
    
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

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
            $pesertaList = PesertaPelatihanModel::where('pelatihan_id', $request->kegiatan_id)
                ->where('status', 'Approved')
                ->get();

            foreach ($pesertaList as $peserta) {
                SuratModel::create([
                    'peserta_pelatihan_id' => $peserta->peserta_pelatihan_id,
                    'user_id' => $peserta->user_id,
                    'file_surat' => $fileName
                ]);
            }
        } else {
            $pesertaList = PesertaSertifikasiModel::where('sertifikasi_id', $request->kegiatan_id)
                ->where('status', 'Approved')
                ->get();

            foreach ($pesertaList as $peserta) {
                SuratModel::create([
                    'peserta_sertifikasi_id' => $peserta->peserta_sertifikasi_id,
                    'user_id' => $peserta->user_id,
                    'file_surat' => $fileName
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Surat berhasil diupload untuk semua peserta'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

public function upload_bukti($id, $jenis_kegiatan)
{
    try {
        if ($jenis_kegiatan == 'pelatihan') {
            $data = PelatihanModel::findOrFail($id);
            $nama_kegiatan = $data->nama_pelatihan;
        } else {
            $data = SertifikasiModel::findOrFail($id);
            $nama_kegiatan = $data->nama_sertifikasi;
        }

        $data = [
            'kegiatan_id' => $id,
            'jenis_kegiatan' => $jenis_kegiatan,
            'nama_kegiatan' => $nama_kegiatan,
            'jenis' => JenisModel::all()
        ];
        
        return view('surat_tugas.upload_bukti_ajax', $data);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }
}

public function store_bukti(Request $request)
{
    if (!$request->ajax()) {
        return redirect('/');
    }

    $validator = Validator::make($request->all(), [
        'nama_sertif' => 'required|string|max:255',
        'no_sertif' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'masa_berlaku' => 'required|date|after:tanggal',
        'jenis_id' => 'required|exists:m_jenis,jenis_id',
        'nama_vendor' => 'required|string|max:255',
        'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal.',
            'msgField' => $validator->errors()
        ]);
    }

    try {
        DB::beginTransaction();

        // Handle file upload
        $bukti = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            if ($request->jenis_kegiatan == 'pelatihan') {
                $file->move(storage_path('app/public/pelatihan'), $fileName);
                
                UploadPelatihanModel::create([
                    'user_id' => Auth::id(),
                    'nama_sertif' => $request->nama_sertif,
                    'no_sertif' => $request->no_sertif,
                    'tanggal' => $request->tanggal,
                    'masa_berlaku' => $request->masa_berlaku,
                    'jenis_id' => $request->jenis_id,
                    'nama_vendor' => $request->nama_vendor,
                    'bukti' => $fileName
                ]);
            } else {
                $file->move(storage_path('app/public/sertifikasi'), $fileName);
                
                UploadSertifikasiModel::create([
                    'user_id' => Auth::id(),
                    'nama_sertif' => $request->nama_sertif,
                    'no_sertif' => $request->no_sertif,
                    'tanggal' => $request->tanggal,
                    'masa_berlaku' => $request->masa_berlaku,
                    'jenis_id' => $request->jenis_id,
                    'nama_vendor' => $request->nama_vendor,
                    'bukti' => $fileName
                ]);
            }

            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Bukti berhasil diupload.'
            ]);
        }

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'status' => false,
            'message' => 'Gagal mengupload bukti: ' . $e->getMessage()
        ]);
    }
}
    // public function upload_sertif($id)
    // {
    //     try {
    //         if (request('jenis') == 'pelatihan') {
    //             $data = PelatihanModel::findOrFail($id);
    //             $nama_kegiatan = $data->nama_pelatihan;
    //         } else {
    //             $data = SertifikasiModel::findOrFail($id);
    //             $nama_kegiatan = $data->nama_sertifikasi;
    //         }
    
    //         return view('surat_tugas.upload_sertif', [
    //             'kegiatan_id' => $id,
    //             'jenis' => request('jenis'),
    //             'nama_kegiatan' => $nama_kegiatan
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Data tidak ditemukan'
    //         ], 404);
    //     }
    // }

    public function sertif_ajax($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bukti' => 'required|mimes:pdf,doc,docx|max:2048',
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
            $uploadPath = public_path('uploads_sertif/surat');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            // Simpan file
            $file = $request->file('bukti');
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
                $surat->bukti = $fileName;
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
                $surat->bukti = $fileName;
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