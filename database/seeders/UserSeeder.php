<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 2,
                'level_id' => 2,  // Sesuaikan dengan data level yang ada
                'nip' => '0987654321', // Contoh NIP
                'nama' => 'Dosen',
                'username' => 'dosen',  // Username sesuai dengan yang diinginkan
                'email' => 'dosen@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
            ]
        ];

        DB::table('m_user')->insert($data);
    }
}
