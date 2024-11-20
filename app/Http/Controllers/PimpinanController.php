<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class PimpinanController extends Controller
{
    public function index() 
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pimpin',
            'list'  => ['Home', 'Pimpinan']
        ];

        $page = (object) [
            'title' => 'Daftar Pimpinan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pimpinan'; 

        $level = LevelModel::all(); // ambil data level untuk filter level

        return view('user.pimpinan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Ambil data Pimpinan dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $users = UserModel::where('level_id', 3) // level_id 2 untuk Pimpinan
            ->select('user_id', 'nip', 'nama', 'username', 'email', 'level_id')
            ->with('level');

        
        // Filter data dosen berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn() 
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi

                $btn  = '<button onclick="modalAction(\''.url('/pimpinan/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/pimpinan/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/pimpinan/' . $user->user_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> '; 

                return $btn; 
            })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }

    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.pimpinan.create_ajax')->with('level', $level);
    }

    public function store_ajax(Request $request) 
    {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                'level_id'  => 'required|integer',
                'nip'       => 'required|string|min:3|max:10|unique:m_user,nip',
                'nama'      => 'required|string|max:100',
                'username' => 'required|string|min:3|unique:m_user,username',
                'email'     => 'required|email|unique:m_user,email',
                'password'  => 'required|min:6'
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors() // pesan error validasi
                ]);
            }
            UserModel::create([
                'level_id' => 3, // Set level_id untuk Pimpinan
                'nip' => $request->nip,
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data pimpinan berhasil disimpan'
            ]);
        }
        redirect('/');
    }

}
