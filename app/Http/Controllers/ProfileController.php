<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use App\Models\DosenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

public function update(Request $request, $id)
{
    request()->validate([
        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', 
        'nama'     => 'required|string|max:100',
        'old_password' => 'nullable|string',
        'password' => 'nullable|min:6',
    ]);

    $user = UserModel::find($id);

    // Update username dan nama
    $user->username = $request->username;
    $user->nama = $request->nama;

    // Update password jika ada
    if ($request->filled('old_password')) {
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
        } else {
            return back()
                ->withErrors(['old_password' => __('Password lama salah')])
                ->withInput();
        }
    }

    // Upload avatar jika ada
    if (request()->hasFile('avatar')) {
        if ($user->avatar && file_exists(storage_path('app/public/photos/' . $user->avatar))) {
            Storage::delete('app/public/photos/'.$user->avatar);
        }

        $file = $request->file('avatar');
        $fileName = $file->hashName() . '.' . $file->getClientOriginalExtension();
        $request->avatar->move(storage_path('app/public/photos'), $fileName);
        $user->avatar = $fileName;
    }

    $user->save();

    // Khusus untuk dosen, update bidang dan mata kuliah
    // Khusus untuk dosen, update bidang dan mata kuliah
// Khusus untuk dosen, update bidang dan mata kuliah
if ($user->level_id == 3) {
    try {
        // Hapus data lama
        DosenModel::where('user_id', $user->user_id)->delete();
        
        // Insert data bidang jika ada
        if ($request->has('bidang_id')) {
            foreach($request->bidang_id as $bidang_id) {
                DosenModel::create([
                    'user_id' => $user->user_id,
                    'bidang_id' => $bidang_id,
                    'mk_id' => null
                ]);
            }
        }

        // Insert data mata kuliah jika ada
        if ($request->has('mk_id')) {
            foreach($request->mk_id as $mk_id) {
                DosenModel::create([
                    'user_id' => $user->user_id,
                    'bidang_id' => null,
                    'mk_id' => $mk_id
                ]);
            }
        }
        
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal menyimpan data. Silakan pilih Bidang dan Mata Kuliah.');
    }
}

    return back()->with('status', 'Profile berhasil diperbarui');
}

public function index()
{
    $user = UserModel::findOrFail(Auth::id());
    
    // Ambil data bidang dan mata kuliah yang sudah dipilih
    if ($user->level_id == 3) {
        $selectedBidang = DosenModel::where('user_id', $user->user_id)
            ->whereNotNull('bidang_id')
            ->pluck('bidang_id')
            ->toArray();
            
        $selectedMatkul = DosenModel::where('user_id', $user->user_id)
            ->whereNotNull('mk_id')
            ->pluck('mk_id')
            ->toArray();
            
        $bidang = BidangModel::all();
        $matkul = MatkulModel::all();
    } else {
        $selectedBidang = [];
        $selectedMatkul = [];
        $bidang = [];
        $matkul = [];
    }

    return view('profile', [
        'user' => $user,
        'bidang' => $bidang,
        'matkul' => $matkul,
        'selectedBidang' => $selectedBidang,
        'selectedMatkul' => $selectedMatkul,
        'breadcrumb' => (object) [
            'title' => 'Data Profile',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Profile', 'url' => url('/profile')]
            ]
        ],
        'activeMenu' => 'profile'
    ]);
}
}