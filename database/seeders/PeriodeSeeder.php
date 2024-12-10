<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['periode_tahun' => 1920, 'created_at' => now(), 'updated_at' => now()],
            ['periode_tahun' => 2021, 'created_at' => now(), 'updated_at' => now()],
            ['periode_tahun' => 2122, 'created_at' => now(), 'updated_at' => now()], 
            ['periode_tahun' => 2223, 'created_at' => now(), 'updated_at' => now()],
            ['periode_tahun' => 2324, 'created_at' => now(), 'updated_at' => now()],
            ['periode_tahun' => 2425, 'created_at' => now(), 'updated_at' => now()]
        ];

        DB::table('m_periode')->insert($data);
    }
}
