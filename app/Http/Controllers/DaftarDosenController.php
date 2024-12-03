<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;


class DaftarDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user';
        $level = LevelModel::select('level_id', 'level_nama')
        ->where('level_id', 3)
        ->get();

        return view('daftar_dosen.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'nip', 'nama', 'email', 'username', 'level_id')
            ->with('level')
            ->where('level_id', 3); // Filter hanya untuk level_id = 3

        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // public function create_ajax()
    // {
    //     $level = LevelModel::select('level_id', 'level_nama')
    //     ->where('level_id', 3)
    //     ->get();
    //     return view('daftar_dosen.create_ajax', ['level' => $level]);
    // }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $validator = Validator::make($request->all(), [
                    'level_id' => 'required|exists:m_level,level_id',
                    'nip' => 'required|string|max:20|unique:m_user,nip',
                    'nama' => 'required|string|max:100',
                    'username' => 'required|string|max:20|unique:m_user,username',
                    'email' => 'required|email|max:100|unique:m_user,email',
                    'password' => 'required|min:6|max:255'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal',
                        'msgField' => $validator->errors()
                    ]);
                }

                // Tambahkan data dengan avatar null
                UserModel::create([
                    'level_id' => $request->level_id,
                    'nip' => $request->nip,
                    'nama' => $request->nama,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'avatar' => null  // Tambahkan ini
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil disimpan'
                ]);

            } catch (\Exception $e) {
                Log::error('Error creating user: ' . $e->getMessage());
                
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data'
                ]);
            }
        }

        return redirect('/');
    }

    // public function edit_ajax(string $id)
    // {
    //     $user = UserModel::with('level')->find($id);
    //     $level = LevelModel::select('level_id', 'level_nama')->get();
    //     return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    // }

    // public function update_ajax(Request $request, string $id)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $user = UserModel::find($id);

    //         $rules = [
    //             'level_id' => 'required|exists:m_level,level_id',
    //             'nip' => 'required|string|max:20|unique:m_user,nip,' . $id . ',user_id',
    //             'nama' => 'required|string|max:100',
    //             'username' => 'required|string|max:20|unique:m_user,username,' . $id . ',user_id',
    //             'email' => 'required|email|max:100|unique:m_user,email,' . $id . ',user_id',
    //             'password' => 'nullable|min:6|max:255'
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi gagal',
    //                 'msgField' => $validator->errors()
    //             ]);
    //         }

    //         if ($user) {
    //             $updateData = $request->except(['password']);
    //             if ($request->filled('password')) {
    //                 $updateData['password'] = Hash::make($request->password);
    //             }
                
    //             $user->update($updateData);
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data user berhasil diperbarui'
    //             ]);
    //         }

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Data tidak ditemukan'
    //         ]);
    //     }

    //     return redirect('/');
    // }

    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);

            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $daftar_dosen = UserModel::with(['level', 'upload_sertifikat'])->find($id);
        return view('daftar_dosen.show_ajax', ['daftar_dosen' => $daftar_dosen]);
    }

    // public function import()
    // {
    //     return view('user.import');
    // }

    // public function import_ajax(Request $request)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             'file_user' => ['required', 'mimes:xlsx', 'max:1024'],
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi gagal',
    //                 'msgField' => $validator->errors()
    //             ]);
    //         }

    //         $file = $request->file('file_user');
    //         $reader = IOFactory::createReader('Xlsx');
    //         $spreadsheet = $reader->load($file->getRealPath());
    //         $data = $spreadsheet->getActiveSheet()->toArray(null, false, true, true);

    //         $insert = [];
    //         foreach ($data as $key => $row) {
    //             if ($key > 1) {
    //                 $insert[] = [
    //                     'level_id' => $row['A'],
    //                     'nip' => $row['B'],
    //                     'nama' => $row['C'],
    //                     'username' => $row['D'],
    //                     'email' => $row['E'],
    //                     'password' => Hash::make($row['F']),
    //                     'created_at' => now()
    //                 ];
    //             }
    //         }

    //         if (count($insert) > 0) {
    //             UserModel::insertOrIgnore($insert);
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data user berhasil diimport'
    //             ]);
    //         }

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Tidak ada data yang diimport'
    //         ]);
    //     }

    //     return redirect('/');
    // }

    public function export_excel()
    {
        // Ambil semua user kecuali administrator (level_id = 1)
        $users = UserModel::with('level')
            ->where('level_id', '!=', 1)
            ->get();
    
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Setup header seperti sebelumnya
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Username');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'Level');
    
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    
        $row = 2;
        $no = 1;
    
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $user->nip);
            $sheet->setCellValue('C' . $row, $user->nama);
            $sheet->setCellValue('D' . $row, $user->username);
            $sheet->setCellValue('E' . $row, $user->email);
            $sheet->setCellValue('F' . $row, $user->level->level_nama);
    
            $row++;
            $no++;
        }
    
        // Kode lainnya tetap sama
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data User.xlsx';
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
    }

    // Untuk include admin di export
    // public function export_excel()
    // {
    //     $users = UserModel::with('level')->get();

    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $sheet->setCellValue('A1', 'No');
    //     $sheet->setCellValue('B1', 'NIP');
    //     $sheet->setCellValue('C1', 'Nama');
    //     $sheet->setCellValue('D1', 'Username');
    //     $sheet->setCellValue('E1', 'Email');
    //     $sheet->setCellValue('F1', 'Level');

    //     $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    //     $row = 2;
    //     $no = 1;

    //     foreach ($users as $user) {
    //         $sheet->setCellValue('A' . $row, $no);
    //         $sheet->setCellValue('B' . $row, $user->nip);
    //         $sheet->setCellValue('C' . $row, $user->nama);
    //         $sheet->setCellValue('D' . $row, $user->username);
    //         $sheet->setCellValue('E' . $row, $user->email);
    //         $sheet->setCellValue('F' . $row, $user->level->level_nama);

    //         $row++;
    //         $no++;
    //     }

    //     foreach (range('A', 'F') as $columnID) {
    //         $sheet->getColumnDimension($columnID)->setAutoSize(true);
    //     }

    //     $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $filename = 'Data User.xlsx';

    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header("Content-Disposition: attachment; filename=$filename");
    //     $writer->save("php://output");
    // }

    public function exportTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Level ID');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Username');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'Password');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Template_User.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename");
        $writer->save("php://output");
        exit;
    }
    
    public function export_pdf()
    {
        // Ambil semua user kecuali administrator (level_id = 1)
        $users = UserModel::with('level')
            ->where('level_id', '!=', 1)
            ->get();
            
        $pdf = Pdf::loadView('user.export_pdf', ['users' => $users]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        return $pdf->stream('Data User ' . date('Y-m-d H:i:s') . '.pdf');
    }

    // Untuk include admin di export 
    // public function export_pdf()
    // {
    //     $users = UserModel::with('level')->get();
    //     $pdf = Pdf::loadView('user.export_pdf', ['users' => $users]);
    //     $pdf->setPaper('a4', 'portrait');
    //     $pdf->setOption('isRemoteEnabled', true);
    //     return $pdf->stream('Data User ' . date('Y-m-d H:i:s') . '.pdf');
    // }
}
