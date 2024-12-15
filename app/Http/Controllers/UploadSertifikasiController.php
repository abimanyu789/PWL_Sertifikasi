<?php

namespace App\Http\Controllers;

use App\Models\JenisModel;
use App\Models\UploadSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UploadSertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Upload Sertifikat Sertifikasi',
            'list' => ['Home', 'Upload Sertifikasi']
        ];
        
        $page = (object) [
            'title' => 'Upload sertifikasi yang sudah diikuti',
        ];
     
        $activeMenu = 'upload_sertifikasi'; 
     
        return view('data_sertifikasi.upload_sertifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request) 
    { 
        $uploadSertifikasi = UploadSertifikasiModel::with(['user', 'jenis'])
            ->where('user_id', Auth::id()); // Ensure only user's own data is shown
    
        return DataTables::of($uploadSertifikasi) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($uploadSertifikasi) { 
                return '
                    <button onclick="modalAction(\'' . url('/upload_sertifikasi/' . $uploadSertifikasi->upload_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>
                    <button onclick="modalAction(\'' . url('/upload_sertifikasi/' . $uploadSertifikasi->upload_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="modalAction(\'' . url('/upload_sertifikasi/' . $uploadSertifikasi->upload_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"></i> Hapus</button>
                ';
            })
            ->rawColumns(['aksi']) 
            ->make(true); 
    } 

    public function create_ajax()
    {
        $data = [
            'jenis' => JenisModel::all(),
        ];
            
        return view('data_sertifikasi.upload_sertifikasi.create_ajax', $data);
    }

    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'nama_sertif' => 'required|string|max:255',
            'no_sertif' => 'required|string|max:255|unique:upload_sertifikasi,no_sertif',
            'tanggal' => 'required|date',
            'masa_berlaku' => 'required|date|after:tanggal',
            'jenis_id' => 'required|exists:m_jenis,jenis_id',
            'nama_vendor' => 'required|string|max:255',
            'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
            DB::beginTransaction();

            // Handle file upload
            $bukti = null;
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(storage_path('app/public/sertifikasi'), $fileName);
                $bukti = $fileName;
            }

            // Create record
            UploadSertifikasiModel::create([
                'user_id' => Auth::id(),
                'nama_sertif' => $request->nama_sertif,
                'no_sertif' => $request->no_sertif,
                'tanggal' => $request->tanggal,
                'masa_berlaku' => $request->masa_berlaku,
                'jenis_id' => $request->jenis_id,
                'nama_vendor' => $request->nama_vendor,
                'bukti' => $bukti
            ]);

            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Sertifikat sertifikasi berhasil disimpan.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan sertifikat sertifikasi: ' . $e->getMessage()
            ]);
        }
    }

    return redirect('/');
}

public function show_ajax($id)
{
    $sertifikasi = UploadSertifikasiModel::with(['user', 'jenis'])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    return view('data_sertifikasi.upload_sertifikasi.show_ajax', compact('sertifikasi'));
}
public function edit_ajax($id)
{
    $sertifikasi = UploadSertifikasiModel::where('user_id', Auth::id())->findOrFail($id);
    $jenis = JenisModel::all();
    return view('data_sertifikasi.upload_sertifikasi.edit_ajax', compact('sertifikasi', 'jenis'));
}

public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $sertifikasi = UploadSertifikasiModel::where('user_id', Auth::id())->findOrFail($id);

        $rules = [
            'nama_sertif' => 'required|string|max:255',
            'no_sertif' => 'required|string|max:255|unique:upload_sertifikasi,no_sertif,'.$id.',upload_id',
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
            DB::beginTransaction();

            // Handle file upload if there's new file
            if ($request->hasFile('bukti')) {
                // Delete old file
                if ($sertifikasi->bukti) {
                    Storage::delete('public/sertifikasi/'.$sertifikasi->bukti);
                }

                $file = $request->file('bukti');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(storage_path('app/public/sertifikasi'), $fileName);
                $sertifikasi->bukti = $fileName;
            }

            $sertifikasi->nama_sertif = $request->nama_sertif;
            $sertifikasi->no_sertif = $request->no_sertif;
            $sertifikasi->tanggal = $request->tanggal;
            $sertifikasi->masa_berlaku = $request->masa_berlaku;
            $sertifikasi->jenis_id = $request->jenis_id;
            $sertifikasi->nama_vendor = $request->nama_vendor;
            $sertifikasi->save();

            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ]);
        }
    }
    return redirect('/');
}

public function confirm_ajax($id)
{
    $sertifikasi = UploadSertifikasiModel::with('jenis')
        ->where('user_id', Auth::id())
        ->findOrFail($id);
        
    return view('data_sertifikasi.upload_sertifikasi.confirm_ajax', compact('sertifikasi'));
}

public function delete_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        try {
            DB::beginTransaction();
            
            $sertifikasi = UploadSertifikasiModel::where('user_id', Auth::id())->findOrFail($id);
            
            // Delete file if exists
            if ($sertifikasi->bukti) {
                Storage::delete('public/sertifikasi/' . $sertifikasi->bukti);
            }
            
            $sertifikasi->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }
}
}