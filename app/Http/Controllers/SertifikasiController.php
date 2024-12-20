<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SertifikasiModel;
use App\Models\MatkulModel;
use App\Models\JenisModel;
use App\Models\PeriodeModel;
use App\Models\PesertaSertifikasiModel;
use App\Models\DosenModel;
use App\Models\UserModel;
use App\Models\VendorModel;
use App\Models\NotifikasiModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // import excel
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // import pdf
use Illuminate\Support\Facades\DB;

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
        $levelSertifikasi = ['Profesi', 'Keahlian'];
     
        return view('data_sertifikasi.sertifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'levelSertifikasi' => $levelSertifikasi,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 

        $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode']);
        
        if ($request->level_sertifikasi) {
            $sertifikasi->where('level_sertifikasi', $request->level_sertifikasi);
        }

        Log::info('Jumlah data sertifikasi: ' . $sertifikasi->count());
    
        return DataTables::of($sertifikasi) 
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($sertifikasi) {  // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                
                // Hitung jumlah peserta yang sudah terdaftar
                $jumlah_peserta = PesertaSertifikasiModel::where('sertifikasi_id', $sertifikasi->sertifikasi_id)->count();

                // Tampilkan tombol tambah peserta hanya jika kuota belum penuh
                if ($jumlah_peserta < $sertifikasi->kuota) {
                    $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/tambah_peserta') . '\')" class="btn btn-success btn-sm">Tambah Peserta</button>';
                } else {
                    $btn .= '<button class="btn btn-secondary btn-sm" disabled>Kuota Penuh</button>';
                }

                return $btn;
            }) 
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true); 
    } 
    
    
    public function create_ajax()
    {
        
        $data = [
            'vendor' => VendorModel::all(),
            'jenis' => JenisModel::all(),
            'mata_kuliah' => MatkulModel::all(),
            'periode' => PeriodeModel::all()
        ];
            
        return view('data_sertifikasi.sertifikasi.create_ajax', $data);
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_sertifikasi' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:255',
                'tanggal' => 'required|date',
                'kuota' => 'required|numeric|min:1',
                'level_sertifikasi' => 'required|in:Profesi,Keahlian',
                'vendor_id' => 'required|exists:m_vendor,vendor_id',
                'jenis_id' => 'required|exists:m_jenis,jenis_id',
                'mk_id' => 'required|exists:m_mata_kuliah,mk_id',
                'periode_id' => 'required|exists:m_periode,periode_id'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ], 422);
            }
    
            try {
                // Explicit data creation
                $data = [
                    'nama_sertifikasi' => $request->nama_sertifikasi,
                    'deskripsi' => $request->deskripsi,
                    'tanggal' => $request->tanggal,
                    'kuota' => (int)$request->kuota,
                    'level_sertifikasi' => $request->level_sertifikasi,
                    'vendor_id' => (int)$request->vendor_id,
                    'jenis_id' => (int)$request->jenis_id,
                    'mk_id' => (int)$request->mk_id,
                    'periode_id' => (int)$request->periode_id
                ];
    
                $sertifikasi = SertifikasiModel::create($data);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Data sertifikasi berhasil disimpan.'
                ], 200);
    
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data sertifikasi: ' . $e->getMessage()
                ], 500);
            }
        }
    
        return redirect('/');
    }

    public function tambah_peserta($id)
    {
        try {
            $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);

            // Hitung jumlah peserta
            $jumlah_peserta = $sertifikasi->peserta_sertifikasi->count();

            if ($jumlah_peserta >= $sertifikasi->kuota) {
                return view('data_sertifikasi.sertifikasi.tambah_peserta', [
                    'sertifikasi' => $sertifikasi,
                    'error' => 'Kuota sertifikasi sudah penuh!'
                ]);
            }

            // Subquery untuk mendapatkan semua bidang dan mata kuliah per dosen
            $dosenInfo = DB::table('m_user as u')
                ->select(
                    'u.user_id',
                    DB::raw('GROUP_CONCAT(DISTINCT b.bidang_id) as bidang_ids'),
                    DB::raw('GROUP_CONCAT(DISTINCT mk.mk_id) as mk_ids'),
                    DB::raw('GROUP_CONCAT(DISTINCT b.bidang_nama) as bidang_names'),
                    DB::raw('GROUP_CONCAT(DISTINCT mk.mk_nama) as mk_names')
                )
                ->leftJoin('m_bidang as b', 'u.bidang_id', '=', 'b.bidang_id')
                ->leftJoin('m_mata_kuliah as mk', 'u.mk_id', '=', 'mk.mk_id')
                ->where('u.level_id', 3)
                ->groupBy('u.user_id');

            // Query utama
            $users = DB::table('m_user as u')
                ->joinSub($dosenInfo, 'dosen_info', function($join) {
                    $join->on('u.user_id', '=', 'dosen_info.user_id');
                })
                ->leftJoin('upload_sertifikasi as up', function($join) {
                    $join->on('u.user_id', '=', 'up.user_id');
                        
                })
                ->select(
                    'u.user_id',
                    'u.nama',
                    'dosen_info.bidang_names',
                    'dosen_info.mk_names',
                    DB::raw('COUNT(DISTINCT up.upload_id) as jumlah_sertifikasi'),
                    DB::raw('CASE 
                        WHEN FIND_IN_SET(' . $sertifikasi->jenis_id . ', (
                            SELECT GROUP_CONCAT(jenis_id) 
                            FROM m_bidang 
                            WHERE FIND_IN_SET(bidang_id, dosen_info.bidang_ids)
                        )) THEN 1 
                        ELSE 0 
                    END as is_matching_bidang')
                )
                ->where('u.level_id', 3)
                ->whereNotExists(function($query) use ($id) {
                    $query->select(DB::raw(1))
                        ->from('peserta_sertifikasi as ps')
                        ->whereRaw('ps.user_id = u.user_id')
                        ->where('ps.sertifikasi_id', $id);
                })
                ->groupBy('u.user_id', 'u.nama', 'dosen_info.bidang_names', 'dosen_info.mk_names', 'dosen_info.bidang_ids')
                ->orderByDesc('jumlah_sertifikasi')  // Kemudian berdasarkan jumlah sertifikasi
                ->orderByDesc('is_matching_bidang')
                ->get();

            // Log untuk debugging
            Log::info('Users Query:', [
                'count' => $users->count(),
                'data' => $users->toArray()
            ]);

            // informasi sisa kuota
            $sisa_kuota = $sertifikasi->kuota - $jumlah_peserta;
            $sertifikasi->sisa_kuota = $sisa_kuota;

            return view('data_sertifikasi.sertifikasi.tambah_peserta', compact('sertifikasi', 'users'));

        } catch (\Exception $e) {
            Log::error('Error in tambah_peserta: ' . $e->getMessage());
            return view('data_sertifikasi.sertifikasi.tambah_peserta', [
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function kirim(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            // Validasi input
            if (!$request->has('user_ids')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pilih minimal satu dosen'
                ]);
            }

            $sertifikasi = SertifikasiModel::findOrFail($id);
            $user_ids = $request->user_ids;

            // Validasi kuota
            $existing_count = PesertaSertifikasiModel::where('sertifikasi_id', $id)->count();
            $new_total = $existing_count + count($user_ids);
            
            if ($new_total > $sertifikasi->kuota) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jumlah peserta melebihi kuota yang tersedia'
                ]);
            }

            $berhasil = 0;
            foreach ($user_ids as $user_id) {
                // Cek duplikasi
                $exists = PesertaSertifikasiModel::where('sertifikasi_id', $id)
                    ->where('user_id', $user_id)
                    ->exists();
                
                if (!$exists) {
                    $peserta = new PesertaSertifikasiModel();
                    $peserta->sertifikasi_id = $id;
                    $peserta->user_id = $user_id;
                    $peserta->status = 'Pending';
                    $peserta->save();
                
                    $berhasil++;
                }
            }

            if ($berhasil > 0) {
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => "Berhasil menambahkan $berhasil peserta sertifikasi"
                ]);
            }

            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada peserta yang berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan peserta sertifikasi: ' . $e->getMessage()
            ]);
        }
    }

    public function edit_ajax(string $id)
    {
        $sertifikasi = SertifikasiModel::with('jenis', 'vendor', 'mata_kuliah', 'periode')->find($id);
        $jenis = JenisModel::all();
        $vendor = VendorModel::all();
        $mata_kuliah = MatkulModel::all();
        $periode = PeriodeModel::all();
        // return view('data_sertifikasi.sertifikasi.edit_ajax', compact('sertifikasi', 'bidang', 'vendor', 'level_sertifikasi'));
        return view('data_sertifikasi.sertifikasi.edit_ajax', ['sertifikasi' => $sertifikasi , 'jenis' => $jenis, 'vendor' => $vendor,'mata_kuliah' => $mata_kuliah, 'periode' => $periode]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_sertifikasi' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:255',
                'tanggal' => 'required|date',
                'kuota' => 'required|numeric|min:1',
                'level_sertifikasi' => 'required|in:Profesi,Keahlian',
                'vendor_id' => 'required|exists:m_vendor,vendor_id',
                'jenis_id' => 'required|exists:m_jenis,jenis_id',
                'mk_id' => 'required|exists:m_mata_kuliah,mk_id',
                'periode_id' => 'required|exists:m_periode,periode_id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $sertifikasi = SertifikasiModel::find($id);

            if ($sertifikasi) {
                $sertifikasi->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data sertifikasi berhasil diperbarui.',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $sertifikasi = SertifikasiModel::find($id);
        return view('data_sertifikasi.sertifikasi.confirm_ajax', ['sertifikasi' => $sertifikasi]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $sertifikasi = SertifikasiModel::find($id);

            if ($sertifikasi) {
                $sertifikasi->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data sertifikasi berhasil dihapus.',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }

        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        
        $sertifikasi = SertifikasiModel::find($id);
        return view('data_sertifikasi.sertifikasi.show_ajax', ['sertifikasi' => $sertifikasi]);
    }

    public function import()
    {
        return view('data_sertifikasi.sertifikasi.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_sertifikasi' => ['required', 'mimes:xlsx', 'max:1024'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_sertifikasi');
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray(null, false, true, true);

            $insert = [];
            foreach ($data as $key => $row) {
                if ($key > 1) {
                    $insert[] = [
                        'nama_sertifikasi' => $row['A'],
                        'deskripsi' => $row['B'],
                        'tanggal' => $row['C'],
                        'kuota' => $row['D'],
                        'level_sertifikasi' => $row['E'],
                        'vendor_id' => $row['F'],
                        'jenis_id' => $row['G'],
                        'mk_id' => $row['H'],
                        'periode_id' => $row['I'],
                        'created_at' => now()
                    ];
                }
            }

            if (count($insert) > 0) {
                SertifikasiModel::insertOrIgnore($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil diimport'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }

        return redirect('/');
    }
    public function export_excel()
    {
        $sertifikasi = SertifikasiModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Sertifikasi');
        $sheet->setCellValue('C1', 'Deskripsi');
        $sheet->setCellValue('D1', 'Tanggal');
        $sheet->setCellValue('E1', 'Kuota');
        $sheet->setCellValue('F1', 'Level Sertifikasi');
        $sheet->setCellValue('G1', 'Vendor');
        $sheet->setCellValue('H1', 'Jenis');
        $sheet->setCellValue('I1', 'Mata Kuliah');
        $sheet->setCellValue('J1', 'Periode');

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $row = 2;
        $no = 1;

        foreach ($sertifikasi as $s) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $s->nama_sertifikasi);
            $sheet->setCellValue('C' . $row, $s->deskripsi);
            $sheet->setCellValue('D' . $row, $s->tanggal);
            $sheet->setCellValue('E' . $row, $s->kuota);
            $sheet->setCellValue('F' . $row, $s->level_sertifikasi);
            $sheet->setCellValue('G' . $row, $s->vendor->vendor_nama ?? '-');
            $sheet->setCellValue('H' . $row, $s->jenis->jenis_nama ?? '-');
            $sheet->setCellValue('I' . $row, $s->mata_kuliah->mk_nama ?? '-');
            $sheet->setCellValue('J' . $row, $s->periode->periode_tahun ?? '-');
            
            $row++;
            $no++;
        }

        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Sertifikasi.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
    }


    public function export_pdf()
    {
        $sertifikasi = SertifikasiModel::all();
        $pdf = Pdf::loadView('data_sertifikasi.sertifikasi.export_pdf', ['sertifikasi' => $sertifikasi]);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption('isRemoteEnabled', true); // Aktifkan akses remote untuk gambar
        return $pdf->stream('Data Sertifikasi ' . date('Y-m-d H:i:s') . '.pdf');

    }

    public function exportTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Nama Sertifikasi');
        $sheet->setCellValue('B1', 'Deskripsi');
        $sheet->setCellValue('C1', 'Tanggal (YYYY-MM-DD)');
        $sheet->setCellValue('D1', 'Kuota');
        $sheet->setCellValue('E1', 'Level Sertifikasi');
        $sheet->setCellValue('F1', 'Vendor ID');
        $sheet->setCellValue('G1', 'Jenis ID');
        $sheet->setCellValue('H1', 'Mata Kuliah ID');
        $sheet->setCellValue('I1', 'Periode ID');

        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Template_Sertifikasi.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
        exit;
    }
    
}