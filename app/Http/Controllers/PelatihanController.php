<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel;
use App\Models\MatkulModel;
use App\Models\JenisModel;
use App\Models\PeriodeModel;
use App\Models\NotifikasiModel;
use App\Models\VendorModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // import excel
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // import pdf
use Illuminate\Support\Facades\DB;

class PelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pelatihan',
            'list' => ['Home', 'Pelatihan']
        ];
        
        $page = (object) [
            'title' => 'Daftar pelatihan yang terdaftar dalam sistem',
        ];
     
        $activeMenu = 'pelatihan'; 
        $levelPelatihan = ['Nasional', 'Internasional'];
     
        return view('data_pelatihan.pelatihan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'levelPelatihan' => $levelPelatihan,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 

        $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode']);
        
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
    
    public function create_ajax()
    {
        
        $data = [
            'vendor' => VendorModel::all(),
            'jenis' => JenisModel::all(),
            'mata_kuliah' => MatkulModel::all(),
            'periode' => PeriodeModel::all()
        ];
            
        return view('data_pelatihan.pelatihan.create_ajax', $data);
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:255',
                'tanggal' => 'required|date',
                'kuota' => 'required|numeric|min:1',
                'lokasi' => 'required|string|max:255',
                'biaya' => 'required|numeric|min:0',
                'level_pelatihan' => 'required|in:Nasional,Internasional',
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
                ]);
            }
    
            try {
                PelatihanModel::create($request->all());
                
                return response()->json([
                    'status' => true,
                    'message' => 'Data pelatihan berhasil disimpan.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data pelatihan.'
                ]);
            }
        }
    
        return redirect('/');
    }
    public function tambah_peserta($id)
    {
        try {
            $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->findOrFail($id);
            
            // Hitung jumlah peserta yang sudah terdaftar
        $jumlah_peserta = DB::table('peserta_pelatihan')
            ->where('pelatihan_id', $id)
            ->count();

        // Cek apakah kuota sudah penuh
        if ($jumlah_peserta >= $pelatihan->kuota) {
            return response()->json([
                'status' => false,
                'message' => 'Kuota pelatihan sudah penuh!'
            ]);
        }

        // 1. Cek dan create data dosen untuk user level 3 yang belum ada di t_dosen
            $users = DB::table('m_user')
                ->where('level_id', 3)
                ->whereNotExists(function($query) {
                    $query->select(DB::raw(1))
                        ->from('t_dosen')
                        ->whereRaw('t_dosen.user_id = m_user.user_id');
                })
                ->get();

            // 2. Untuk setiap user level dosen yang belum ada di t_dosen
            foreach($users as $user) {
                // Cari bidang yang sesuai dengan jenis pelatihan
                $bidang = DB::table('m_bidang')
                    ->where('jenis_id', $pelatihan->jenis_id)
                    ->first();

                // Insert ke t_dosen dengan bidang dan mata kuliah yang sesuai
                DB::table('t_dosen')->insert([
                    'user_id' => $user->user_id,
                    'bidang_id' => $bidang ? $bidang->bidang_id : null,
                    'mk_id' => $pelatihan->mk_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // 3. Ambil daftar dosen yang eligible
            $dosen = DB::table('t_dosen as d')
                ->select(
                    'd.dosen_id',
                    'm.user_id',
                    'm.nama',
                    'b.bidang_nama',
                    'mk.mk_nama as mata_kuliah',
                    DB::raw('COUNT(DISTINCT pp.peserta_pelatihan_id) as jumlah_pelatihan')
                )
                ->join('m_user as m', 'd.user_id', '=', 'm.user_id')
                ->leftJoin('m_bidang as b', 'd.bidang_id', '=', 'b.bidang_id')
                ->leftJoin('m_mata_kuliah as mk', 'd.mk_id', '=', 'mk.mk_id')
                ->leftJoin('peserta_pelatihan as pp', 'm.user_id', '=', 'pp.user_id')
                ->where('m.level_id', 3)
                ->where(function($query) use ($pelatihan) {
                    $query->where('b.jenis_id', $pelatihan->jenis_id)
                        ->orWhere('d.mk_id', $pelatihan->mk_id);
                })
                ->whereNotExists(function($query) use ($id) {
                    $query->select(DB::raw(1))
                        ->from('peserta_pelatihan as pp2')
                        ->whereRaw('pp2.user_id = m.user_id')
                        ->where('pp2.pelatihan_id', $id);
                })
                ->groupBy('d.dosen_id', 'm.user_id', 'm.nama', 'b.bidang_nama', 'mk.mk_nama')
                ->orderBy('jumlah_pelatihan', 'asc')
                ->get();

                // Tambahkan informasi sisa kuota
                $sisa_kuota = $pelatihan->kuota - $jumlah_peserta;
                $pelatihan->sisa_kuota = $sisa_kuota;


            return view('data_pelatihan.pelatihan.tambah_peserta', compact('pelatihan', 'dosen'));

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }

    }
    public function kirim(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::beginTransaction();
    
                $pelatihan = PelatihanModel::findOrFail($id);
    
                // Validate request
                $validator = Validator::make($request->all(), [
                    'user_ids' => 'required|array|min:1',
                    'user_ids.*' => 'required|exists:m_user,user_id'
                ]);
    
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal.',
                        'msgField' => $validator->errors()
                    ]);
                }
    
                // Check quota
                if (count($request->user_ids) > $pelatihan->kuota) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Jumlah peserta melebihi kuota pelatihan.'
                    ]);
                }
    
                foreach ($request->user_ids as $user_id) {
                    DB::table('peserta_pelatihan')->insert([
                        'pelatihan_id' => $id,
                        'user_id' => $user_id,
                        'status' => 'Pending',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
    
                // Kirim notifikasi ke pimpinan (level_id = 2)
                $pimpinan = DB::table('m_user')->where('level_id', 2)->first();
                if ($pimpinan) {
                    NotifikasiModel::create([
                        'user_id' => $pimpinan->user_id,
                        'title' => 'Pengajuan Peserta Pelatihan Baru',
                        'message' => "Ada pengajuan peserta baru untuk pelatihan {$pelatihan->nama_pelatihan}",
                        'type' => 'pengajuan_peserta',
                        'reference_id' => $id
                    ]);
                }
    
                DB::commit();
    
                return response()->json([
                    'status' => true,
                    'message' => 'Peserta pelatihan berhasil ditambahkan.'
                ]);
    
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menambahkan peserta pelatihan.'
                ]);
            }
        }
    
        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $pelatihan = PelatihanModel::with('jenis', 'vendor', 'mata_kuliah', 'periode')->find($id);
        $jenis = JenisModel::all();
        $vendor = VendorModel::all();
        $mata_kuliah = MatkulModel::all();
        $periode = PeriodeModel::all();
        // return view('data_pelatihan.pelatihan.edit_ajax', compact('pelatihan', 'bidang', 'vendor', 'level_pelatihan'));
        return view('data_pelatihan.pelatihan.edit_ajax', ['pelatihan' => $pelatihan , 'jenis' => $jenis, 'vendor' => $vendor,'mata_kuliah' => $mata_kuliah, 'periode' => $periode]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:255',
                'tanggal' => 'required|date',
                'kuota' => 'required|numeric|min:1',
                'lokasi' => 'required|string|max:255',
                'biaya' => 'required|numeric|min:0',
                'level_pelatihan' => 'required|in:Nasional,Internasional',
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

            $pelatihan = PelatihanModel::find($id);

            if ($pelatihan) {
                $pelatihan->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data pelatihan berhasil diperbarui.',
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
        $pelatihan = PelatihanModel::find($id);
        return view('data_pelatihan.pelatihan.confirm_ajax', ['pelatihan' => $pelatihan]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pelatihan = PelatihanModel::find($id);

            if ($pelatihan) {
                $pelatihan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data pelatihan berhasil dihapus.',
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
        
        $pelatihan = PelatihanModel::find($id);
        return view('data_pelatihan.pelatihan.show_ajax', ['pelatihan' => $pelatihan]);
    }

    public function import()
    {
        return view('data_pelatihan.pelatihan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_pelatihan' => ['required', 'mimes:xlsx', 'max:1024'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_pelatihan');
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray(null, false, true, true);

            $insert = [];
            foreach ($data as $key => $row) {
                if ($key > 1) {
                    $insert[] = [
                        'nama_pelatihan' => $row['A'],
                        'deskripsi' => $row['B'],
                        'tanggal' => $row['C'],
                        'kuota' => $row['D'],
                        'lokasi' => $row['E'],
                        'biaya' => $row['F'],
                        'level_pelatihan' => $row['G'],
                        'vendor_id' => $row['H'],
                        'jenis_id' => $row['I'],
                        'mk_id' => $row['J'],
                        'periode_id' => $row['K'],
                        'created_at' => now()
                    ];
                }
            }

            if (count($insert) > 0) {
                PelatihanModel::insertOrIgnore($insert);
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
        $pelatihan = PelatihanModel::with(['vendor', 'jenis', 'mata_kuliah', 'periode'])->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Pelatihan');
        $sheet->setCellValue('C1', 'Deskripsi');
        $sheet->setCellValue('D1', 'Tanggal');
        $sheet->setCellValue('E1', 'Kuota');
        $sheet->setCellValue('F1', 'Lokasi');
        $sheet->setCellValue('G1', 'Biaya');
        $sheet->setCellValue('H1', 'Level Pelatihan');
        $sheet->setCellValue('I1', 'Vendor');
        $sheet->setCellValue('J1', 'Jenis');
        $sheet->setCellValue('K1', 'Mata Kuliah');
        $sheet->setCellValue('L1', 'Periode');

        $sheet->getStyle('A1:L1')->getFont()->setBold(true);

        $row = 2;
        $no = 1;

        foreach ($pelatihan as $p) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $p->nama_pelatihan);
            $sheet->setCellValue('C' . $row, $p->deskripsi);
            $sheet->setCellValue('D' . $row, $p->tanggal);
            $sheet->setCellValue('E' . $row, $p->kuota);
            $sheet->setCellValue('F' . $row, $p->lokasi);
            $sheet->setCellValue('G' . $row, $p->biaya);
            $sheet->setCellValue('H' . $row, $p->level_pelatihan);
            $sheet->setCellValue('I' . $row, $p->vendor->vendor_nama ?? '-');
            $sheet->setCellValue('J' . $row, $p->jenis->jenis_nama ?? '-');
            $sheet->setCellValue('K' . $row, $p->mata_kuliah->mk_nama ?? '-');
            $sheet->setCellValue('L' . $row, $p->periode->periode_tahun ?? '-');
            
            $row++;
            $no++;
        }

        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Pelatihan.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
    }


    public function export_pdf()
    {
        $pelatihan = PelatihanModel::all();
        $pdf = Pdf::loadView('data_pelatihan.pelatihan.export_pdf', ['pelatihan' => $pelatihan]);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption('isRemoteEnabled', true); // Aktifkan akses remote untuk gambar
        return $pdf->stream('Data Pelatihan ' . date('Y-m-d H:i:s') . '.pdf');

    }

    public function exportTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Nama Pelatihan');
        $sheet->setCellValue('B1', 'Deskripsi');
        $sheet->setCellValue('C1', 'Tanggal (YYYY-MM-DD)');
        $sheet->setCellValue('D1', 'Kuota');
        $sheet->setCellValue('E1', 'Lokasi');
        $sheet->setCellValue('F1', 'Biaya');
        $sheet->setCellValue('G1', 'Level Pelatihan');
        $sheet->setCellValue('H1', 'Vendor ID');
        $sheet->setCellValue('I1', 'Jenis ID');
        $sheet->setCellValue('J1', 'Mata Kuliah ID');
        $sheet->setCellValue('K1', 'Periode ID');

        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Template_Pelatihan.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
        exit;
    }
    
}