<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile(Request $request)
{
    try {
        $user = DB::table('m_user')
            ->select('nama', 'email', 'username', 'avatar')  // Tambahkan avatar
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load profile: ' . $e->getMessage()
        ], 500);
    }
}

public function update(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required|string',
            'nama' => 'required|string',
            'nip' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'email' => $request->email,
            'username' => $request->username,
            'nama' => $request->nama,
            'nip' => $request->nip,
            'updated_at' => now(),
        ];

        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('public/avatars', $avatarName);
            $data['avatar'] = $avatarName;

            // Delete old avatar if exists
            $user = DB::table('m_user')->where('user_id', $request->user()->id)->first();
            if ($user && $user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }
        }

        DB::table('m_user')
            ->where('user_id', $request->user()->id)
            ->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update profile: ' . $e->getMessage()
        ], 500);
    }
}
}