<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // import excel
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // import pdf

class VendorController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Vendor',
            'list' => ['Home', 'Vendor']
        ];
        $page = (object) [
            'title' => 'Daftar vendor yang terdaftar dalam sistem',
        ];
        $activeMenu = 'vendor'; // set menu yang sedang aktif
        $vendor = VendorModel::select('vendor_id', 'vendor_nama')->get(); // Ambil vendor untuk filter
        return view('vendor.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vendor' => $vendor, // Ubah ke $levels
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request) 
    { 

        $vendor = VendorModel::select('vendor_id', 'vendor_nama', 'alamat', 'kota', 'no_telp', 'alamat_web'); 
    
        return DataTables::of($vendor) 
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($vendor) {  // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/vendor/' . $vendor->vendor_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendor/' . $vendor->vendor_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendor/' . $vendor->vendor_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            }) 
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true); 
    } 

    public function create_ajax()
    {
        
        $data = [
            'vendor' => VendorModel::all(),
        ];
            
        return view('vendor.create_ajax', $data);
    }

    public function store_ajax(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_nama' => 'required|string|max:100',
                'alamat' => 'required|string|max:255',
                'kota' => 'required|string|max:100',
                'no_telp' => 'required|string|max:20',
                'alamat_web' => 'required|url|max:100'
            ]);
    
            if ($validator->fails()) {
                Log::error('Validation Errors: ', $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ]);
            }            
    
            VendorModel::create([
                'vendor_nama' => $request->vendor_nama,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'no_telp' => $request->no_telp,
                'alamat_web' => $request->alamat_web
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Data vendor berhasil disimpan'
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error saving vendor: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data'
            ]);
        }
    }

    public function edit_ajax(string $id)
    {
        $vendor = VendorModel::find($id);
        return view('vendor.edit_ajax', ['vendor' => $vendor]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'vendor_nama' => 'required|string|max:255',
                'alamat' => 'nullable|string|max:255',
                'kota' => 'nullable|string|max:100',
                'no_telp' => 'nullable|string|max:15',
                'alamat_web' => 'nullable|url|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $vendor = VendorModel::find($id);

            if ($vendor) {
                $vendor->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data vendor berhasil diperbarui.',
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
        $vendor = VendorModel::find($id);
        return view('vendor.confirm_ajax', ['vendor' => $vendor]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $vendor = VendorModel::find($id);

            if ($vendor) {
                $vendor->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data vendor berhasil dihapus.',
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
        $vendor = VendorModel::find($id);
        return view('vendor.show_ajax', ['vendor' => $vendor]);
    }

    public function import()
    {
        return view('vendor.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_vendor' => ['required', 'mimes:xlsx', 'max:1024'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $file = $request->file('file_vendor');
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray(null, false, true, true);

            $insert = [];
            foreach ($data as $key => $row) {
                if ($key > 1) {
                    $insert[] = [
                        'vendor_nama' => $row['A'],
                        'alamat' => $row['B'],
                        'kota' => $row['C'],
                        'no_telp' => $row['D'],
                        'alamat_web' => $row['E'],
                        'created_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                VendorModel::insertOrIgnore($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data vendor berhasil diimport.',
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
        $vendor = VendorModel::all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nama Vendor');
        $sheet->setCellValue('C1', 'Alamat');
        $sheet->setCellValue('D1', 'Kota');
        $sheet->setCellValue('E1', 'No. Telepon');
        $sheet->setCellValue('F1', 'Website');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        $no = 1;

        foreach ($vendor as $v) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $v->vendor_nama);
            $sheet->setCellValue('C' . $row, $v->alamat);
            $sheet->setCellValue('D' . $row, $v->kota);
            $sheet->setCellValue('E' . $row, $v->no_telp);
            $sheet->setCellValue('F' . $row, $v->alamat_web);

            $row++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Vendor.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
    }


    public function export_pdf()
    {
        $vendor = VendorModel::all();
        $pdf = Pdf::loadView('vendor.export_pdf', ['vendor' => $vendor]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true); // Aktifkan akses remote untuk gambar
        return $pdf->stream('Data Vendor ' . date('Y-m-d H:i:s') . '.pdf');

    }

}
