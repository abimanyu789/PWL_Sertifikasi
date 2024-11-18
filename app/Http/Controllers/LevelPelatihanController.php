<?php

namespace App\Http\Controllers;

use App\Models\LevelPelatihanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class LevelPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level Pelatihan',
            'list' => ['Home', 'Level Pelatihan']
        ];
        $page = (object) [ 
            'title' => 'Daftar level pelatihan yang terdaftar dalam sistem'
        ];
        $activeMenu = 'level_pelatihan'; // set menu yang sedang aktif
        $level_pelatihan = LevelPelatihanModel::all();
        return view('data_pelatihan.level_pelatihan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level_pelatihan' => $level_pelatihan, 'activeMenu' => $activeMenu]);
    }
    
    public function list(Request $request)
    {
        $level_pelatihan = LevelPelatihanModel::select('level_pelatihan_id', 'level_pelatihan_kode', 'level_pelatihan_nama');
        return DataTables::of($level_pelatihan)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level_pelatihan) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/level_pelatihan/' . $level_pelatihan->level_pelatihan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level_pelatihan/' . $level_pelatihan->level_pelatihan_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level_pelatihan/' . $level_pelatihan->level_pelatihan_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // Menampilkan halaman detail user
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_pelatihan_kode' => 'required|string|min:3|unique:m_level_pelatihan,level_pelatihan_kode',
                'level_pelatihan_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            LevelPelatihanModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data level pelatihan berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Menampilkan halaman form edit level ajax
    public function edit_ajax(string $id)
    {
        $level_pelatihan = LevelPelatihanModel::find($id);
        return view('data_pelatihan.level_pelatihan.edit_ajax', ['level_pelatihan' => $level_pelatihan]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_pelatihan_kode' => 'required|max:10|unique:m_level_pelatihan,level_pelatihan_kode,' . $id . ',level_pelatihan_id',
                'level_pelatihan_nama' => 'required|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = LevelPelatihanModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $level_pelatihan = LevelPelatihanModel::find($id);
        return view('data_pelatihan.level_pelatihan.confirm_ajax', ['level_pelatihan' => $level_pelatihan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level_pelatihan = LevelPelatihanModel::find($id);
            if ($level_pelatihan) {
                $level_pelatihan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show_ajax(string $id){
        $level_pelatihan = LevelPelatihanModel::find($id);

        return view('data_pelatihan.level_pelatihan.show_ajax', ['level_pelatihan' => $level_pelatihan]);
    }

    public function export_excel(){
        // Ambil data Level yang akan di export
        $level_pelatihan = LevelPelatihanModel::select('level_pelatihan_kode', 'level_pelatihan_nama')->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Level Pelatihan Kode');
        $sheet->setCellValue('C1', 'Level Pelatihan Nama');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true); // bold Header

        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach($level_pelatihan as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->level_pelatihan_kode);
            $sheet->setCellValue('C'.$baris, $value->level_pelatihan_nama);
            $baris++;
            $no++;
        }

        foreach(range('A','C') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Level Pelatihan'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Level Pelatihan'.date('Y-m-d H:i:s').'.xlsx';

        header('Content-Type: appplication/vdn.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    } // end function export_excelD

    public function export_pdf()
    {
        $level_pelatihan = LevelPelatihanModel::select('level_pelatihan_kode', 'level_pelatihan_nama')->orderBy('level_pelatihan_kode')->get();

        $pdf = Pdf::loadView('data_pelatihan.level_pelatihan.export_pdf', ['level_pelatihan' => $level_pelatihan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->render();

        return $pdf->stream('Data Level Pelatihan ' . date('Y-m-d H:i:s') . '.pdf');
    }

}
