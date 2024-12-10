<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSertifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['jenis_kode' => 'PROF', 'jenis_nama' => 'Profesi'],
            ['jenis_kode' => 'KEAH', 'jenis_nama' => 'Keahlian'],
        ];
        DB::table('m_level_sertifikasi')->insert($data);
    }
}
