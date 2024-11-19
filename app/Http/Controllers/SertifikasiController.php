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
        $sertifikasi = SertifikasiModel::all(); // Ambil data jenis sertifikasi untuk dropdown filter
        return view('data_sertifikasi.sertifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'sertifikasi' => $sertifikasi,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data sertifikasi dalam bentuk JSON untuk DataTables
    public function list(Request $request) 
    { 
        $sertifikasi = SertifikasiModel::select('sertifikasi_id', 'nama_sertifikasi', 'tanggal', 'tanggal_berlaku', 'bidang_id', 'jenis_id')
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

    public function create_ajax()
    {
        return view('data_sertifikasi.sertifikat.create_ajax');
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
        $sertifikasi = SertifikasiModel::find($id);
        return view('data_sertifikasi.sertifikat.edit_ajax', ['sertifikasi' => $sertifikasi]);
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

    public function confirm_ajax(string $id)
    {
        $sertifikasi = SertifikasiModel::find($id);
        return view('data_sertifikasi.sertifikat.confirm_ajax', ['sertifikasi' => $sertifikasi]);
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
        return view('data_sertifikasi.sertifikat.show_ajax', ['sertifikasi' => $sertifikasi]);
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
        $sertifikasi = SertifikasiModel::all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Sertifikasi');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Tanggal Berlaku');
        $sheet->setCellValue('E1', 'Bidang');
        $sheet->setCellValue('F1', 'Jenis Sertifikasi');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        $no = 1;

        foreach ($sertifikasi as $p) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $p->nama_sertifikasi);
            $sheet->setCellValue('C' . $row, $p->tanggal);
            $sheet->setCellValue('D' . $row, $p->tanggal_berlaku);
            $sheet->setCellValue('E' . $row, $item->bidang->nama_bidang ?? '-'); // Nama Bidang
            $sheet->setCellValue('F' . $row, $item->jenis->jenis_nama ?? '-'); // Nama Jenis Sertifikasi

            $row++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
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
        $pdf = Pdf::loadView('data_sertifikasi.sertifikat.export_pdf', ['sertifikasi' => $sertifikasi]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true); // Aktifkan akses remote untuk gambar
        return $pdf->stream('Data sertifikasi ' . date('Y-m-d H:i:s') . '.pdf');

    }
}