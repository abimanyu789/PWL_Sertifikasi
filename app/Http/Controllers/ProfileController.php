<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\BidangModel;
use App\Models\MatkulModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = UserModel::findOrFail(Auth::id());
        $breadcrumb = (object) [
            'title' => 'Data Profile',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Profile', 'url' => url('/profile')]
            ]
        ];

        $activeMenu = 'profile';

        // Ambil data bidang dan mata kuliah jika user adalah dosen
        $bidang = [];
        $matkul = [];
        if ($user->level_id == 3) { // level_id 3 adalah dosen
            $bidang = BidangModel::all();
            $matkul = MatKulModel::all();
        }

        return view('profile', compact('user', 'bidang', 'matkul'), [
            'breadcrumb' => $breadcrumb, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::find($id);
        
        $rules = [
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', 
            'nama' => 'required|string|max:100',
            'old_password' => 'nullable|string',
            'password' => 'nullable|min:6',
        ];

        // Fitur Bidang minat dan mata kuliah khusus Dosen
if ($user->level_id == 3) {
    // Cek apakah ada data yang dipilih
    $user->bidang_id = $request->has('bidang_id') ? implode(',', $request->bidang_id) : null;
    $user->mk_id = $request->has('mk_id') ? implode(',', $request->mk_id) : null;
}

        request()->validate($rules);

        // Update data user
        $user->username = $request->username;
        $user->nama = $request->nama;

        // Update password jika diisi
        if ($request->filled('old_password')) {
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
            } else {
                return back()
                    ->withErrors(['old_password' => __('Password lama salah')])
                    ->withInput();
            }
        }

        // Handle upload avatar
        if (request()->hasFile('avatar')) {
            if ($user->avatar && file_exists(storage_path('app/public/photos/' . $user->avatar))) {
                Storage::delete('app/public/photos/'.$user->avatar);
            }

            $file = $request->file('avatar');
            $fileName = $file->hashName() . '.' . $file->getClientOriginalExtension();
            $request->avatar->move(storage_path('app/public/photos'), $fileName);
            $user->avatar = $fileName;
        }

        // // Update bidang dan mata kuliah untuk dosen
        // if ($user->level_id == 3) {
        //     $user->bidang_id = implode(',', $request->bidang_id);
        //     $user->mk_id = implode(',', $request->mk_id);
        // }

        $user->save();

        return back()->with('status', 'Profile berhasil diperbarui');
    }
}