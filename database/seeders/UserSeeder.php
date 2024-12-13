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
            // [
            //     'user_id' => 2,
            //     'level_id' => 2,  // Sesuaikan dengan data level yang ada
            //     'nip' => '09876543', // Contoh NIP
            //     'nama' => 'Pimpinan',
            //     'username' => 'pimpinan',  // Username sesuai dengan yang diinginkan
            //     'email' => 'pimpinan@example.com',  // Email sesuai dengan yang diinginkan
            //     'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
            //     'avatar' => ''
            // ],
            // [
            //     'user_id' => 3,
            //     'level_id' => 3,  // Sesuaikan dengan data level yang ada
            //     'nip' => '12341234', // Contoh NIP
            //     'nama' => 'Dosen',
            //     'username' => 'dosen',  // Username sesuai dengan yang diinginkan
            //     'email' => 'dosen@example.com',  // Email sesuai dengan yang diinginkan
            //     'password' => Hash::make('123456'), // Jangan lupa untuk enkripsi password
            //     'avatar' => ''
            // ],
            [
                'user_id' => 4,
                'level_id' => 3,
                'nip' => '199107042019031021',
                'nama' => 'Ade Ismail',
                'username' => 'ade',
                'email' => 'adeismail@example.com',
                'password' => Hash::make('password123'),
                'avatar' => ''
            ],
            [
                'user_id' => 5,
                'level_id' => 3,
                'nip' => '198902012019031009',
                'nama' => 'Irsyad Arif Mashudi',
                'username' => 'irsyad',
                'email' => 'irsyadarif@example.com',
                'password' => Hash::make('password123'),
                'avatar' => ''
            ],
            [
                'user_id' => 6,
                'level_id' => 3,
                'nip' => '199205172019031020',
                'nama' => 'Muhammad Shulhan Khairy',
                'username' => 'khairy',
                'email' => 'shulhankhairy@example.com',
                'password' => Hash::make('password123'),
                'avatar' => ''
            ],
            [
                'user_id' => 7,
                'level_id' => 3,
                'nip' => '198902102019031019',
                'nama' => 'Moch. Zawaruddin Abdullah',
                'username' => 'zawa',
                'email' => 'mzawaruddin@example.com',
                'password' => Hash::make('password123'),
                'avatar' => ''
            ],
            [
                'user_id' => 8,
                'level_id' => 3,
                'nip' => '198609232015041001',
                'nama' => 'Usman Nurhasan',
                'username' => 'usman',
                'email' => 'usmannurhasan@example.com',
                'password' => Hash::make('password123'),
                'avatar' => ''
            ],
            [
                'user_id' => 9,
                'level_id' => 3,
                'nip' => '198906212019031013',
                'nama' => 'Yoppy Yunhasnawa',
                'username' => 'yoppy',
                'email' => 'yoppyyunhasnawa@example.com',
                'password' => Hash::make('password123'),
                'avatar' => ''
            ],
            [
                'user_id' => 10,
                'level_id' => 3,
                'nip' => '195912081985031004',
                'nama' => 'Ekojono',
                'username' => 'ekojono',
                'email' => 'ekojono@example.com',
                'password' => Hash::make('password123'),
                'avatar' => ''
            ],

        ];

        DB::table('m_user')->insert($data);
    }
}
