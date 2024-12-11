<?php

namespace App\Http\Controllers;

use App\Models\JenisModel;
use App\Models\UploadPelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UploadPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Upload Sertifikat Pelatihan',
            'list' => ['Home', 'Upload Pelatihan']
        ];
        
        $page = (object) [
            'title' => 'Upload pelatihan yang sudah diikuti',
        ];
     
        $activeMenu = 'upload_pelatihan'; 
     
        return view('data_pelatihan.upload_pelatihan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request) 
    { 
        $uploadPelatihan = UploadPelatihanModel::with(['user', 'jenis'])
            ->where('user_id', Auth::id()); // Ensure only user's own data is shown
    
        return DataTables::of($uploadPelatihan) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($uploadPelatihan) {
                $btn = '<div class="btn-group">';
                $btn .= '<button onclick="modalAction(\'' . url('upload_pelatihan.show', $uploadPelatihan->upload_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('upload_pelatihan.edit', $uploadPelatihan->upload_id) . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="deleteAction(\'' . url('upload_pelatihan.destroy', $uploadPelatihan->upload_id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                $btn .= '</div>';
                return $btn;
            }) 
            ->rawColumns(['aksi']) 
            ->make(true); 
    } 

    public function create_ajax()
    {
        $data = [
            'jenis' => JenisModel::all(),
        ];
            
        return view('data_pelatihan.upload_pelatihan.create_ajax', $data);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_sertif' => 'required|string|max:255',
                'no_sertif' => 'required|string|max:255|unique:upload_pelatihan,no_sertif',
                'tanggal' => 'required|date',
                'masa_berlaku' => 'required|date|after:tanggal',
                'jenis_id' => 'required|exists:m_jenis,jenis_id',
                'nama_vendor' => 'required|string|max:255',
                'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ];

        $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
    
            try {
                UploadPelatihanModel::create($request->all());
                
                return response()->json([
                    'status' => true,
                    'message' => 'Sertifikat pelatihan berhasil disimpan.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan sertifikat pelatihan.'
                ]);
            }
        }
    
        return redirect('/');
    }
}