<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

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
        $activeMenu = 'vendor';
        $vendors = VendorModel::all();
        return view('vendor.index', compact('breadcrumb', 'page', 'vendors', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $vendors = VendorModel::select('vendor_id', 'vendor_nama', 'alamat', 'kota', 'no_telp', 'alamat_web', 'created_at', 'updated_at');
        return DataTables::of($vendors)
            ->addIndexColumn()
            ->addColumn('aksi', function ($vendor) {
                $btn = '<a href="' . url('/vendor/' . $vendor->vendor_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/vendor/' . $vendor->vendor_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/vendor/' . $vendor->vendor_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Vendor',
            'list' => ['Home', 'Vendor', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah data vendor baru',
        ];
        $activeMenu = 'vendor';
        return view('data_vendor.vendor.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function storeAjax(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'vendor_nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'alamat_web' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
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

    public function show($id)
    {
        $vendor = VendorModel::findOrFail($id);
        $breadcrumb = (object) [
            'title' => 'Detail Vendor',
            'list' => ['Home', 'Vendor', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail data vendor',
        ];
        $activeMenu = 'vendor';
        return view('data_vendor.vendor.show', compact('vendor', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function edit($id)
    {
        $vendor = VendorModel::findOrFail($id);
        $breadcrumb = (object) [
            'title' => 'Edit Vendor',
            'list' => ['Home', 'Vendor', 'Edit']
        ];
        $page = (object) [
            'title' => 'Perbarui data vendor',
        ];
        $activeMenu = 'vendor';
        return view('data_vendor.vendor.edit', compact('vendor', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'vendor_nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'alamat_web' => 'required|url|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vendor = VendorModel::findOrFail($id);
        $vendor->update($request->all());
        return redirect('/vendor')->with('success', 'Data vendor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $vendor = VendorModel::findOrFail($id);
        $vendor->delete();
        return redirect('/vendor')->with('success', 'Data vendor berhasil dihapus.');
    }

    public function confirmAjax($id)
    {
        $vendor = VendorModel::findOrFail($id);
        return view('data_vendor.vendor.confirm_ajax', compact('vendor'));
    }

    public function createAjax()
    {
        return view('vendor.create_ajax');
    }

    public function editAjax($id)
    {
        $vendor = VendorModel::findOrFail($id);
        return view('data_vendor.vendor.edit_ajax', compact('vendor'));
    }

    public function showAjax($id)
    {
        $vendor = VendorModel::findOrFail($id);
        return view('data_vendor.vendor.show_ajax', compact('vendor'));
    }

    public function import()
{
    return view('vendor.import');  
}

    public function importAjax(Request $request)
    {
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

    public function exportExcel()
    {
        $vendors = VendorModel::all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Vendor');
        $sheet->setCellValue('C1', 'Alamat');
        $sheet->setCellValue('D1', 'Kota');
        $sheet->setCellValue('E1', 'No. Telepon');
        $sheet->setCellValue('F1', 'Alamat Web');
        $sheet->setCellValue('G1', 'Dibuat');
        $sheet->setCellValue('H1', 'Diperbarui');

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        $row = 2;
        $no = 1;

        foreach ($vendors as $vendor) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $vendor->vendor_nama);
            $sheet->setCellValue('C' . $row, $vendor->alamat);
            $sheet->setCellValue('D' . $row, $vendor->kota);
            $sheet->setCellValue('E' . $row, $vendor->no_telp);
            $sheet->setCellValue('F' . $row, $vendor->alamat_web);
            $sheet->setCellValue('G' . $row, $vendor->created_at);
            $sheet->setCellValue('H' . $row, $vendor->updated_at);

            $row++;
            $no++;
        }

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Vendor.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
    }

    public function exportPdf()
    {
        $vendors = VendorModel::all();
        $pdf = Pdf::loadView('vendor.export_pdf', compact('vendors'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        return $pdf->stream('Data Vendor ' . date('Y-m-d H:i:s') . '.pdf');
    }
}