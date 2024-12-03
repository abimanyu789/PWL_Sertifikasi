<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SertifikasiModel;
use App\Models\JenisSertifikasiModel;
use App\Models\BidangModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // import excel
use Barryvdh\DomPDF\Facade\Pdf; // import pdf

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
        $jenisSertifikasi = JenisSertifikasiModel::select('jenis_id', 'jenis_nama')->get(); // Ambil data jenis sertifikasi untuk dropdown filter
        return view('data_sertifikasi.sertifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenisSertifikasi' => $jenisSertifikasi,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data sertifikasi dalam bentuk JSON untuk DataTables
    public function list(Request $request) 
    { 
        $sertifikasi = SertifikasiModel::select('sertifikasi_id', 'nama_sertifikasi', 'tanggal', 'tanggal_berlaku', 'bidang_id', 'jenis_id')
                    ->with(['bidang', 'jenis_sertifikasi']);  // Gunakan nama relasi yang benar
        
        return DataTables::of($sertifikasi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($sertifikasi) {  
                $btn  = '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }    

    public function create_ajax()
    {
        $data = [
            'bidang' => BidangModel::all(),
            'jenis' => JenisSertifikasiModel::all()
        ];
        return view('data_sertifikasi.sertifikasi.create_ajax', $data);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_sertifikasi' => 'required|string|max:100',
                'tanggal' => 'required|date',
                'tanggal_berlaku' => 'required|date',
                'bidang_id' => 'required|integer',
                'jenis_id' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            SertifikasiModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data sertifikasi berhasil disimpan.',
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $sertifikasi = SertifikasiModel::with(['bidang', 'jenis_sertifikasi'])->findOrFail($id);
        $bidang = BidangModel::select('bidang_id', 'bidang_nama')->get();
        $jenis = JenisSertifikasiModel::select('jenis_id', 'jenis_nama')->get();

        return view('data_sertifikasi.sertifikasi.edit_ajax', compact('sertifikasi', 'bidang', 'jenis'));
    }
    
    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_sertifikasi' => 'required|string|max:100',
                'tanggal' => 'required|date',
                'tanggal_berlaku' => 'required|date',
                'bidang_id' => 'required|integer',
                'jenis_id' => 'required|integer',
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

    public function confirm_ajax(string $id){
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

    public function show_ajax(string $id){
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
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
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
                        'tanggal' => $row['B'],
                        'tanggal_berlaku' => $row['C'],
                        'bidang_id' => $row['D'],
                        'jenis_id' => $row['E'],
                        'created_at' => now(),

                    ];
                }
            }

            if (count($insert) > 0) {
                SertifikasiModel::insertOrIgnore($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data sertifikasi berhasil diimport.',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport.',
            ]);
        }

        return redirect('/');
    }

    public function export_excel()
    {
        $sertifikasi = SertifikasiModel::with(['bidang', 'jenis_sertifikasi'])->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Sertifikasi');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Tanggal Berlaku');
        $sheet->setCellValue('E1', 'Bidang');
        $sheet->setCellValue('F1', 'Jenis Sertifikasi');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Isi data
        $row = 2;
        $no = 1;

        foreach ($sertifikasi as $s) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $s->nama_sertifikasi);
            $sheet->setCellValue('C' . $row, $s->tanggal);
            $sheet->setCellValue('D' . $row, $s->tanggal_berlaku);
            $sheet->setCellValue('E' . $row, $s->bidang->bidang_nama);
            $sheet->setCellValue('F' . $row, $s->jenis_sertifikasi->jenis_nama);
        
            $row++;
            $no++;
        }        

        // Atur ukuran kolom agar otomatis
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Simpan file Excel
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
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true); // Aktifkan akses remote untuk gambar
        return $pdf->stream('Data sertifikasi ' . date('Y-m-d H:i:s') . '.pdf');

    }

    public function exportTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Sertifikasi');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Tanggal Berlaku');
        $sheet->setCellValue('E1', 'Bidang');
        $sheet->setCellValue('F1', 'Jenis Sertifikasi');


        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Template_Sertifikasi.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
        exit;
    }
}