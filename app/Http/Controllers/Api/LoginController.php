<?php

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
// use Tymon\JWTAuth\Facades\JWTAuth;
// use App\Models\UserModel;

// class LoginController extends Controller
// {
//     public function __invoke(Request $request)
//     {
//         // set validation
//         $validator = Validator::make($request->all(), [
//             'nip' => 'required|string|max:20',
//             'password' => 'required|string'
//         ]);

//         // if validation fails
//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Validasi gagal',
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         // get credentials from request
//         $credentials = $request->only('nip', 'password');

//         // attempt to create token and login
//         try {
//             if (!$token = JWTAuth::attempt($credentials)) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'NIP atau Password salah'
//                 ], 401);
//             }

//             // get authenticated user
//             $user = UserModel::with('level')
//                     ->where('nip', $request->nip)
//                     ->first();

//             // if auth success
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Login berhasil',
//                 'user' => [
//                     'user_id' => $user->user_id,
//                     'nip' => $user->nip,
//                     'nama' => $user->nama,
//                     'email' => $user->email,
//                     'level' => [
//                         'id' => $user->level->level_id,
//                         'nama' => $user->level->level_nama,
//                         'kode' => $user->level->level_kode
//                     ]
//                 ],
//                 'token' => $token,
//                 'token_type' => 'bearer'
//             ], 200);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Terjadi kesalahan pada server',
//                 'error' => $e->getMessage()
//             ], 500);
//         }
//     }
// }

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\UserModel;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        // if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // get credentials from request
        $credentials = $request->only('username', 'password');

        // attempt to create a token
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau Password salah'
            ], 401);
        }

        // get authenticated user with level relation
        $user = UserModel::with('level')->find(auth()->user()->user_id);

        // if auth success
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'nama' => $user->nama,
                'level' => [
                    'id' => $user->level->level_id,
                    'nama' => $user->level->level_nama,
                    'kode' => $user->level->level_kode
                ]
            ],
            'token' => $token,
            'token_type' => 'bearer'
        ], 200);
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 0f1a0778deebd95e558bae16a8bfcb49bb799121
