<?php

namespace App\Http\Controllers;

use App\Models\LevelPelatihanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
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

        return view('level_pelatihan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level_pelatihan' => $level_pelatihan, 'activeMenu' => $activeMenu]);
    }
    // Ambil data level_pelatihan dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $level_pelatihans = LevelPelatihanModel::select('level_pelatihan_id', 'level_pelatihan_kode', 'level_pelatihan_nama');

        return DataTables::of($level_pelatihans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level_pelatihan) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/level_pelatihan/' . $level_pelatihan->level_pelatihan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    public function confirm_ajax(string $id)
    {
        $level_pelatihan = LevelPelatihanModel::find($id);
        return view('level_pelatihan.confirm_ajax', ['level_pelatihan' => $level_pelatihan]);
    }
    public function show_ajax(string $id)
    {
        $level_pelatihan = LevelPelatihanModel::find($id);
        return view('level_pelatihan.show_ajax', ['level_pelatihan' => $level_pelatihan]);
    }
    public function export_excel()
    {
        // ambil data level_pelatihan yang akan di export
        $level_pelatihan = LevelPelatihanModel::select('level_pelatihan_id', 'level_pelatihan_kode', 'level_pelatihan_nama')
            ->orderBy('level_pelatihan_id')
            ->get();
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);    // bold header
        $no = 1;        // nomor data dimulai dari 1
        $baris = 2;     // baris data dimulai dari baris ke 2
        foreach ($level_pelatihan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->level_pelatihan_kode);
            $sheet->setCellValue('C' . $baris, $value->level_pelatihan_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);   // set auto size untuk kolom
        }
        $sheet->setTitle('Data Level Pelatihan'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data level_pelatihan ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $level_pelatihan = LevelPelatihanModel::select('level_pelatihan_id', 'level_pelatihan_kode', 'level_pelatihan_nama')
            ->orderBy('level_pelatihan_id')
            ->orderBy('level_pelatihan_kode')
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('level_pelatihan.export_pdf', ['level_pelatihan' => $level_pelatihan]);
        $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // Set true jika ada gambar dari URL
        $pdf->render();
        return $pdf->stream('Data level_pelatihan ' . date('Y-m-d H:i:s') . '.pdf');
    }

}
