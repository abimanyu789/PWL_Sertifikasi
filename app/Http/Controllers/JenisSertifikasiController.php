<?php

namespace App\Http\Controllers;

use App\Models\JenisSertifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class JenisSertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Sertifikasi',
            'list' => ['Home', 'Jenis Sertifikasi']
        ];
        $page = (object) [
            'title' => 'Daftar jenis sertifikasi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'jenis_sertifikasi'; // set menu yang sedang aktif

        $jenis_sertifikasi = JenisSertifikasiModel::all();

        return view('jenis_sertifikasi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jenis_sertifikasi' => $jenis_sertifikasi, 'activeMenu' => $activeMenu]);
    }
    // Ambil data jenis_sertifikasi dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $jenis_sertifikasis = JenisSertifikasiModel::select('jenis_id', 'jenis_kode', 'jenis_nama');

        return DataTables::of($jenis_sertifikasis)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis_sertifikasi) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/jenis_sertifikasi/' . $jenis_sertifikasi->jenis_sertifikasi_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    public function confirm_ajax(string $id)
    {
        $jenis_sertifikasi = JenisSertifikasiModel::find($id);
        return view('jenis_sertifikasi.confirm_ajax', ['jenis_sertifikasi' => $jenis_sertifikasi]);
    }
    public function show_ajax(string $id)
    {
        $jenis_sertifikasi = JenisSertifikasiModel::find($id);
        return view('jenis_sertifikasi.show_ajax', ['jenis_sertifikasi' => $jenis_sertifikasi]);
    }
    public function export_excel()
    {
        // ambil data jenis_sertifikasi yang akan di export
        $jenis_sertifikasi = JenisSertifikasiModel::select('jenis_sertifikasi_id', 'jenis_sertifikasi_kode', 'jenis_sertifikasi_nama')
            ->orderBy('jenis_sertifikasi_id')
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
        foreach ($jenis_sertifikasi as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->jenis_sertifikasi_kode);
            $sheet->setCellValue('C' . $baris, $value->jenis_sertifikasi_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);   // set auto size untuk kolom
        }
        $sheet->setTitle('Data Jenis Sertifikasi'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data jenis_sertifikasi ' . date('Y-m-d H:i:s') . '.xlsx';
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
        $jenis_sertifikasi = JenisSertifikasiModel::select('jenis_sertifikasi_id', 'jenis_sertifikasi_kode', 'jenis_sertifikasi_nama')
            ->orderBy('jenis_sertifikasi_id')
            ->orderBy('jenis_sertifikasi_kode')
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('jenis_sertifikasi.export_pdf', ['jenis_sertifikasi' => $jenis_sertifikasi]);
        $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // Set true jika ada gambar dari URL
        $pdf->render();
        return $pdf->stream('Data jenis_sertifikasi ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
