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
            // [
            //     'user_id' => 1,
            //     'level_id' => 1,  // Sesuaikan dengan data level yang ada
            //     'nip' => '12345678', // Contoh NIP
            //     'nama' => 'Administrator',
            //     'username' => 'admin',  // Username sesuai dengan yang diinginkan
            //     'email' => 'admin@example.com',  // Email sesuai dengan yang diinginkan
            //     'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
            //     'avatar' => ''
            // ],
            [
                'user_id' => 1,
                'level_id' => 1,  // Sesuaikan dengan data level yang ada
                'nip' => '12345678', // Contoh NIP
                'nama' => 'Administrator',
                'username' => 'admin',  // Username sesuai dengan yang diinginkan
                'email' => 'admin@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
            ],
            [
                'user_id' => 2,
<<<<<<< HEAD
                'level_id' => 2,  // Sesuaikan dengan data level yang ada
                'nip' => '09876543', // Contoh NIP
                'nama' => 'Pimpinan',
                'username' => 'pimpinan',  // Username sesuai dengan yang diinginkan
                'email' => 'pimpinan@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('098765'), // Jangan lupa untuk enkripsi password
                'avatar' => ''
            ],
            [
                'user_id' => 3,
                'level_id' => 3,  // Sesuaikan dengan data level yang ada
                'nip' => '12341234', // Contoh NIP
                'nama' => 'Dosen',
                'username' => 'dosen',  // Username sesuai dengan yang diinginkan
                'email' => 'dosen@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('123412'), // Jangan lupa untuk enkripsi password
                'avatar' => ''
=======
                'level_id' => 3,  // Sesuaikan dengan data level yang ada
                'nip' => '0987654321', // Contoh NIP
                'nama' => 'Dosen',
                'username' => 'dosen',  // Username sesuai dengan yang diinginkan
                'email' => 'dosen@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
            ],
            [
                'user_id' => 3,
                'level_id' => 3,  // Sesuaikan dengan data level yang ada
                'nip' => '0122356788', // Contoh NIP
                'nama' => 'DosenSatu',
                'username' => 'dosen1',  // Username sesuai dengan yang diinginkan
                'email' => 'dosen@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
            ],
            [
                'user_id' => 4,
                'level_id' => 2,  // Sesuaikan dengan data level yang ada
                'nip' => '18767610897386', // Contoh NIP
                'nama' => 'Pimpinan',
                'username' => 'pimpinan',  // Username sesuai dengan yang diinginkan
                'email' => 'pimpinan@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
            ],
            [
                'user_id' => 5,
                'level_id' => 2,  // Sesuaikan dengan data level yang ada
                'nip' => '897565621089713', // Contoh NIP
                'nama' => 'PimpinanSatu',
                'username' => 'pimpinan1',  // Username sesuai dengan yang diinginkan
                'email' => 'pimpinan1@example.com',  // Email sesuai dengan yang diinginkan
                'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
>>>>>>> 0f1a0778deebd95e558bae16a8bfcb49bb799121
            ]
        ];

        DB::table('m_user')->insert($data);
    }
}
