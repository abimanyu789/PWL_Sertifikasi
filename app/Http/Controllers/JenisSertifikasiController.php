<?php

namespace App\Http\Controllers;

use App\Models\JenisSertifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
        return view('data_sertifikasi.jenis_sertifikasi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jenis_sertifikasi' => $jenis_sertifikasi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $jenis_sertifikasis = JenisSertifikasiModel::select('jenis_id', 'jenis_kode', 'jenis_nama');
        return DataTables::of($jenis_sertifikasis)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->make(true);
    }

    public function show_ajax(string $id){
        $jenis_sertifikasi = JenisSertifikasiModel::find($id);

        return view('data_sertifikasi.jenis_sertifikasi.show_ajax', ['jenis_sertifikasi' => $jenis_sertifikasi]);
    }

    public function export_excel(){
        // Ambil data Level yang akan di export
        $jenis_sertifikasi = JenisSertifikasiModel::select('jenis_kode', 'jenis_nama')->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Jenis Kode');
        $sheet->setCellValue('C1', 'Jenis Nama');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true); // bold Header

        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach($jenis_sertifikasi as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->jenis_kode);
            $sheet->setCellValue('C'.$baris, $value->jenis_nama);
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
        $jenis_sertifikasi = JenisSertifikasiModel::select('jenis_kode', 'jenis_nama')->orderBy('jenis_kode')->get();

        $pdf = Pdf::loadView('data_sertifikasi.jenis_sertifikasi.export_pdf', ['jenis_sertifikasi' => $jenis_sertifikasi]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true); // Aktifkan akses remote untuk gambar
        return $pdf->stream('Data Level Pelatihan ' . date('Y-m-d H:i:s') . '.pdf');

    }
}
