<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

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

// public function update(Request $request)
// {
//     try {
//         Log::info('=== Start Update Profile ===');
        
//         // Dapatkan user dari token menggunakan Tymon JWTAuth
//         try {
//             $user = JWTAuth::parseToken()->authenticate();
//             if (!$user) {
//                 throw new \Exception('User not authenticated');
//             }
//             Log::info('User authenticated:', ['id' => $user->user_id]);
//         } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
//             throw new \Exception('Token expired');
//         } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
//             throw new \Exception('Token invalid');
//         } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
//             throw new \Exception('Token absent');
//         }

//         $userId = $user->user_id;
//         Log::info('User ID from Auth:', ['id' => $userId]);

//         // Validasi input
//         $validated = $request->validate([
//             'email' => 'required|email',
//             'username' => 'required|string',
//             'nama' => 'required|string',
//             'nip' => 'required|string',
//             'password' => 'nullable|string|min:6', // Tambahkan validasi password
//             'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
//         ]);

//         $data = [
//             'email' => $request->email,
//             'username' => $request->username,
//             'nama' => $request->nama,
//             'nip' => $request->nip,
//             'updated_at' => now()
//         ];

//         // Tambahkan password ke data jika ada
//         if ($request->filled('password')) {
//             $data['password'] = bcrypt($request->password);
//             Log::info('Password will be updated');
//         }

//         // Handle avatar upload jika ada
//         if ($request->hasFile('avatar')) {
//             $currentUser = DB::table('m_user')
//                 ->select('avatar')
//                 ->where('user_id', $userId)
//                 ->first();

//             if ($currentUser && $currentUser->avatar) {
//                 Storage::disk('public')->delete('avatars/' . $currentUser->avatar);
//             }

//             $fileName = time() . '.' . $request->avatar->extension();
//             $request->avatar->storeAs('public/avatars', $fileName);
//             $data['avatar'] = $fileName;
//         }

//         Log::info('Data to update:', array_diff_key($data, ['password' => ''])); // Log data tanpa password

//         DB::beginTransaction();
//         try {
//             $updated = DB::table('m_user')
//                 ->where('user_id', $userId)
//                 ->update($data);

//             if ($updated === 0) {
//                 throw new \Exception('No records updated');
//             }

//             DB::commit();

//             $updatedUser = DB::table('m_user')
//                 ->select('email', 'username', 'nama', 'nip', 'avatar')
//                 ->where('user_id', $userId)
//                 ->first();

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Profil berhasil diperbarui' . ($request->filled('password') ? ' (termasuk password)' : ''),
//                 'data' => $updatedUser
//             ]);
//         } catch (\Exception $e) {
//             DB::rollBack();
//             throw $e;
//         }
//     } catch (\Exception $e) {
//         Log::error('Error updating profile: ' . $e->getMessage());
//         Log::error('Stack trace: ' . $e->getTraceAsString());
        
//         return response()->json([
//             'success' => false,
//             'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
//         ], 500);
//     }
// }
// }

public function update(Request $request)
{
    try {
        Log::info('=== Start Update Profile ===');
        
        // Dapatkan user dari token
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }
            Log::info('User authenticated:', ['id' => $user->user_id]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            throw new \Exception('Token expired');
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            throw new \Exception('Token invalid');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            throw new \Exception('Token absent');
        }

        $userId = $user->user_id;
        Log::info('User ID from Auth:', ['id' => $userId]);

        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'username' => 'required|string',
            'nama' => 'required|string',
            'nip' => 'required|string',
            'password' => 'nullable|string|min:6',
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
        ]);

        // Cek dan buat direktori avatars jika belum ada
        if (!Storage::disk('public')->exists('avatars')) {
            Storage::disk('public')->makeDirectory('avatars');
            Log::info('Created avatars directory');
        }

        $data = [
            'email' => $request->email,
            'username' => $request->username,
            'nama' => $request->nama,
            'nip' => $request->nip,
            'updated_at' => now()
        ];

        // Handle password
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
            Log::info('Password will be updated');
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            Log::info('Processing avatar upload');
            try {
                // Hapus avatar lama jika ada
                $currentUser = DB::table('m_user')
                    ->select('avatar')
                    ->where('user_id', $userId)
                    ->first();

                if ($currentUser && $currentUser->avatar) {
                    Storage::disk('public')->delete('avatars/' . $currentUser->avatar);
                    Log::info('Old avatar deleted:', ['filename' => $currentUser->avatar]);
                }

                // Upload avatar baru
                $fileName = time() . '.' . $request->avatar->extension();
                $uploadPath = $request->avatar->storeAs('public/avatars', $fileName);
                
                Log::info('Avatar upload result:', [
                    'path' => $uploadPath,
                    'filename' => $fileName
                ]);

                if ($uploadPath) {
                    $data['avatar'] = $fileName;
                    Log::info('Avatar filename added to update data');
                } else {
                    throw new \Exception('Failed to store avatar file');
                }
            } catch (\Exception $e) {
                Log::error('Error handling avatar upload: ' . $e->getMessage());
                throw $e;
            }
        }

        Log::info('Data to update:', array_diff_key($data, ['password' => '']));

        DB::beginTransaction();
        try {
            $updated = DB::table('m_user')
                ->where('user_id', $userId)
                ->update($data);

            if ($updated === 0) {
                throw new \Exception('No records updated');
            }

            DB::commit();

            // Ambil data user yang sudah diupdate
            $updatedUser = DB::table('m_user')
                ->select('email', 'username', 'nama', 'nip', 'avatar')
                ->where('user_id', $userId)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui' . ($request->filled('password') ? ' (termasuk password)' : ''),
                'data' => array_merge(
                    (array) $updatedUser,
                    [
                        'avatar_url' => $updatedUser->avatar 
                            ? url('storage/avatars/' . $updatedUser->avatar) 
                            : null
                    ]
                )
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    } catch (\Exception $e) {
        Log::error('Error updating profile: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
        ], 500);
    }
}
}