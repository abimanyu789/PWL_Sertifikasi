<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Fungsi untuk menampilkan halaman profile
    public function index()
    {
        // Dapatkan user berdasarkan ID yang sedang login
    $user = UserModel::findOrFail(Auth::id());

    // Breadcrumb dan active menu
    $breadcrumb = (object) [
        'title' => 'Data Profile',
        'list' => [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Profile', 'url' => url('/profile')]
        ]
    ];

    $activeMenu = 'profile';

    // Pastikan data user dikirim ke view
    return view('profile', compact('user'), [
        'breadcrumb' => $breadcrumb, 
        'activeMenu' => $activeMenu
    ]);
    }

    // Fungsi untuk memperbarui profile
    public function update(Request $request, $id)
    {
        // Validasi input dari form
        request()->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', 
            'nama'     => 'required|string|max:100',
            'old_password' => 'nullable|string',
            'password' => 'nullable|min:5',
        ]);

        $user = UserModel::find($id);

        // Update username dan nama
        $user->username = $request->username;
        $user->nama = $request->nama;

        // Update password jika ada input password lama
        if ($request->filled('old_password')) {
            if (Hash::check($request->old_password, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            } else {
                return back()
                    ->withErrors(['old_password' => __('Password lama salah')])
                    ->withInput();
            }
        }

        // Upload avatar baru jika ada file yang di-upload
        if (request()->hasFile('profile_image')) {
            if ($user->profile_image && file_exists(storage_path('app/public/photos/' . $user->profile_image))) {
                Storage::delete('app/public/photos/'.$user->profile_image);
            }

            $file = $request->file('profile_image');
            $fileName = $file->hashName() . '.' . $file->getClientOriginalExtension();
            $request->profile_image->move(storage_path('app/public/photos'), $fileName);
            $user->profile_image = $fileName;
        }

        // Simpan perubahan
        $user->save();

        return back()->with('status', 'Profile berhasil diperbarui');
    }
}