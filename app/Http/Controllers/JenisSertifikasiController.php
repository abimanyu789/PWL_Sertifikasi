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
                $btn .= '<button onclick="modalAction(\'' . url('/jenis_sertifikasi/' . $jenis_sertifikasi->jenis_sertifikasi_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis_sertifikasi/' . $jenis_sertifikasi->jenis_sertifikasi_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    // Menampilkan halaman form tambah jenis_sertifikasi
    public function create()
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Tambah Jenis Sertifikasi',
            'list' => ['Home', 'Jenis Sertifikasi', 'Tambah']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Tambah jenis sertifikasi baru'
        ];
        // Mengambil data jenis_sertifikasi dari JenisSertifikasiModel untuk ditampilkan di form
        $jenis_sertifikasi = JenisSertifikasiModel::all();
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'jenis_sertifikasi';
        // Menampilkan view 'jenis_sertifikasi.create' dengan data yang sudah diambil
        return view('jenis_sertifikasi.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenis_sertifikasi' => $jenis_sertifikasi,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan data jenis_sertifikasi baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            // kode jenis_sertifikasi harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_jenis_sertifikasi kolom jenis_sertifikasiname
            'jenis_kode' => 'required|string|max:10|unique:m_jenis_sertifikasi,jenis_kode',
            'jenis_nama' => 'required|string|max:100',    // nama harus diisi, berupa string, dan maksimal 100 karakter
        ]);
        // Menyimpan data jenis_sertifikasi baru
        JenisSertifikasiModel::create([
            'jenis_sertifikasi_kode' => $request->jenis_sertifikasi_kode,
            'jenis_sertifikasi_nama' => $request->jenis_sertifikasi_nama,
        ]);
        // Redirect ke halaman /jenis_sertifikasi dengan pesan sukses
        return redirect('/jenis_sertifikasi')->with('success', 'Data jenis_sertifikasi berhasil disimpan');
    }
    // Menampilkan detail jenis_sertifikasi
    public function show(string $id)
    {
        // Mengambil data jenis_sertifikasi berdasarkan id dan relasi jenis_sertifikasi
        $jenis_sertifikasi = JenisSertifikasiModel::find($id);
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Detail Jenis Sertifikasi',
            'list' => ['Home', 'Jenis Sertifikasi', 'Detail']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Detail jenis sertifikasi'
        ];
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'jenis_sertifikasi';
        // Menampilkan view 'jenis_sertifikasi.show' dengan data yang sudah diambil
        return view('jenis_sertifikasi.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenis_sertifikasi' => $jenis_sertifikasi,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menampilkan halaman form edit jenis_sertifikasi
    public function edit(string $id)
    {
        $jenis_sertifikasi = JenisSertifikasiModel::find($id);       // Mengambil data jenis_sertifikasi berdasarkan id
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Edit Jenis Sertifikasi',
            'list' => ['Home', 'Jenis Sertifikasi', 'Edit']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Edit jenis sertifikasi'
        ];

        $activeMenu = 'jenis_sertifikasi';       // set menu yang sedang aktif
        // Menampilkan view 'jenis_sertifikasi.edit' dengan data yang sudah diambil
        return view('jenis_sertifikasi.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'jenis_sertifikasi' => $jenis_sertifikasi,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan perubahan data jenis_sertifikasi
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            // kode_jenis_sertifikasi harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_jenis_sertifikasi,
            // kecuali untuk jenis_sertifikasi dengan id yang sedang diedit
            'jenis_kode' => 'required|string|max:10|unique:m_jenis_sertifikasi,jenis_kode',
            'jenis_nama' => 'required|string|max:100',    // nama harus diisi, berupa string, dan maksimal 60 karakter
        ]);

        // Update data jenis_sertifikasi
        JenisSertifikasiModel::find($id)->update([
            'jenis_kode' => $request->jenis_sertifikasi_kode,
            'jenis_nama' => $request->jenis_sertifikasi_nama,
        ]);
        // Redirect ke halaman /jenis_sertifikasi dengan pesan sukses
        return redirect('/jenis_sertifikasi')->with('success', 'Data jenis_sertifikasi berhasil diubah');
    }
    // Menghapus data jenis_sertifikasi
    public function destroy(string $id)
    {
        // Cek apakah data jenis_sertifikasi dengan id yang dimaksud ada atau tidak
        $check = JenisSertifikasiModel::find($id);
        if (!$check) {
            // Jika data jenis_sertifikasi tidak ditemukan, kembalikan pesan error
            return redirect('/jenis_sertifikasi')->with('error', 'Data jenis_sertifikasi tidak ditemukan');
        }
        try {
            // Hapus data jenis_sertifikasi berdasarkan id
            JenisSertifikasiModel::destroy($id);
            // Jika berhasil, kembalikan pesan sukses
            return redirect('/jenis_sertifikasi')->with('success', 'Data jenis_sertifikasi berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data (misalnya ada data terkait di tabel lain)
            return redirect('/jenis_sertifikasi')->with('error', 'Data jenis_sertifikasi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    // Jobsheet  6
    public function create_ajax()
    {
        $jenis_sertifikasi = JenisSertifikasiModel::all();
        return view('jenis_sertifikasi.create_ajax');
    }
    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa ajax atau ingin JSON
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'jenis_kode' => 'required|string|max:10|unique:m_jenis_sertifikasi,jenis_kode',
                'jenis_nama' => 'required|string|max:100'
            ];

            // Gunakan Validator dari Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
            // Simpan data jenis_sertifikasi
            JenisSertifikasiModel::create($request->all());

            // Jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data jenis_sertifikasi berhasil disimpan',
            ]);
        }
        // Redirect jika bukan request Ajax
        return redirect('/');
    }
    // Menampilkan halaman form edit jenis_sertifikasi ajax
    public function edit_ajax(string $id)
    {
        $jenis_sertifikasi = JenisSertifikasiModel::find($id);
        return view('jenis_sertifikasi.edit_ajax', ['jenis_sertifikasi' => $jenis_sertifikasi]);
    }
    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'jenis_sertifikasi_kode' => 'required|max:10|unique:m_jenis_sertifikasi,jenis_sertifikasi_kode,' . $id . ',jenis_sertifikasi_id',
                'jenis_sertifikasi_nama' => 'required|max:100'
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = JenisSertifikasiModel::find($id);
            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request
                    $request->request->remove('password');
                }
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
        $jenis_sertifikasi = JenisSertifikasiModel::find($id);
        return view('jenis_sertifikasi.confirm_ajax', ['jenis_sertifikasi' => $jenis_sertifikasi]);
    }
    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $jenis_sertifikasi = JenisSertifikasiModel::find($id);
            if ($jenis_sertifikasi) {
                $jenis_sertifikasi->delete();
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
    public function show_ajax(string $id)
    {
        $jenis_sertifikasi = JenisSertifikasiModel::find($id);
        return view('jenis_sertifikasi.show_ajax', ['jenis_sertifikasi' => $jenis_sertifikasi]);
    }
    public function import()
    {
        return view('jenis_sertifikasi.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_jenis_sertifikasi' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_jenis_sertifikasi'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'jenis_sertifikasi_kode' => $value['A'],
                            'jenis_sertifikasi_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    JenisSertifikasiModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
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
        $sheet->setTitle('Data jenis_sertifikasi'); // set title sheet
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
