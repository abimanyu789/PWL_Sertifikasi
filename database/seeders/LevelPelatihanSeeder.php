<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['level_pelatihan_kode' => 'NAS', 'level_pelatihan_nama' => 'Nasional'],
            ['level_pelatihan_kode' => 'INT', 'level_pelatihan_nama' => 'Internasional'],
        ];

        DB::table('m_level_pelatihan')->insert($data);
    }
}
