<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'level_id'=> 2,
                'level_kode' => 'DSN', // Contoh kode level untuk administrator
                'level_nama' => 'Dosen',
            ]
        ];

        DB::table('m_level')->insert($data);
    }
}
