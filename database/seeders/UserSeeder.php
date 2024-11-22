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
                'user_id' => 1,
                'level_id' => 1,  // Sesuaikan dengan data level yang ada
                'nip' => '12345678', // Contoh NIP
                'nama' => 'Administrator',
                'username' => 'admin',  // Username sesuai dengan yang diinginkan
                'email' => 'admin@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
                'avatar' => ''
            ]
        ];

        DB::table('m_user')->insert($data);
    }
}
