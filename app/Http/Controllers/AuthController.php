<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'required',
        ]);

        // Mencari user berdasarkan 'nama'
        $user = MUser::where('nama', $request->nama)->first();

        // Cek apakah user ditemukan dan password sesuai
        if ($user && Hash::check($request->password, $user->password)) {
            // Menyimpan informasi user ke session
            Session::put('user_id', $user->user_id);
            Session::put('nama', $user->nama);

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil.',
                'redirect' => url('/dashboard')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Nama atau password salah.',
                'msgField' => ['nama' => ['Nama tidak ditemukan atau password salah']]
            ]);
        }
    }

    // Proses logout
    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
