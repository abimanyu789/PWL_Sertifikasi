<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel;
use App\Models\LevelPelatihanModel;
use App\Models\BidangModel;
use App\Models\VendorModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // import excel
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // import pdf

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
        $activeMenu = 'pelatihan'; // set menu yang sedang aktif
        $level_pelatihan = LevelPelatihanModel::select('level_pelatihan_id', 'level_pelatihan_nama')->get(); // Ambil level pelatihan untuk filter
        return view('data_pelatihan.pelatihan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level_pelatihan' => $level_pelatihan, // Ubah ke $levels
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 

        $pelatihan = PelatihanModel::query();
        if ($request->has('level_pelatihan_id') && $request->level_pelatihan_id != '') {
            $pelatihan->where('level_pelatihan_id', $request->level_pelatihan_id);
        }

        $pelatihan = PelatihanModel::select('pelatihan_id', 'nama_pelatihan', 'deskripsi','tanggal', 'bidang_id', 'level_pelatihan_id', 'vendor_id') 
                    ->with('level'); 
    
        return DataTables::of($pelatihan) 
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($pelatihan) {  // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->pelatihan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->pelatihan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->pelatihan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            }) 
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true); 
    } 

    public function create_ajax()
    {
        
        $data = [
            'bidang' => BidangModel::all(),
            'level' => LevelPelatihanModel::all(),
            'vendor' => VendorModel::all(),
        ];
            
        return view('data_pelatihan.pelatihan.create_ajax', $data);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan' => 'required|string|max:100',
                'deskripsi' => 'nullable|string|max:255',
                'tanggal' => 'required|date',
                'bidang_id' => 'required|integer',
                'level_pelatihan_id' => 'required|integer',
                'vendor_id' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            PelatihanModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data pelatihan berhasil disimpan.',
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $pelatihan = PelatihanModel::find($id);
        return view('data_pelatihan.pelatihan.edit_ajax', ['pelatihan' => $pelatihan]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan' => 'required|string|max:100',
                'deskripsi' => 'nullable|string|max:255',
                'tanggal' => 'required|date',
                'bidang_id' => 'required|integer',
                'level_pelatihan_id' => 'required|integer',
                'vendor_id' => 'required|integer',
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
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
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
                        'bidang_id' => $row['D'],
                        'level_pelatihan_id' => $row['E'],
                        'vendor_id' => $row['F'],
                        'created_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                PelatihanModel::insertOrIgnore($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data pelatihan berhasil diimport.',
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
        $pelatihan = PelatihanModel::all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Pelatihan');
        $sheet->setCellValue('C1', 'Deskripsi');
        $sheet->setCellValue('D1', 'Tanggal');
        $sheet->setCellValue('E1', 'Bidang');
        $sheet->setCellValue('F1', 'Level Pelatihan');
        $sheet->setCellValue('G1', 'Vendor');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $row = 2;
        $no = 1;

        foreach ($pelatihan as $p) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $p->nama_pelatihan);
            $sheet->setCellValue('C' . $row, $p->deskripsi);
            $sheet->setCellValue('D' . $row, $p->tanggal);
            $sheet->setCellValue('E' . $row, $p->bidang_id);
            $sheet->setCellValue('F' . $row, $p->level_pelatihan_id);
            $sheet->setCellValue('G' . $row, $p->vendor_id);

            $row++;
            $no++;
        }

        foreach (range('A', 'G') as $columnID) {
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
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true); // Aktifkan akses remote untuk gambar
        return $pdf->stream('Data Pelatihan ' . date('Y-m-d H:i:s') . '.pdf');

    }
    
}