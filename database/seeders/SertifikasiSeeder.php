<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SertifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_sertifikasi' => 'Sertifikasi Data Mining',
                'tanggal' => now(),
                'tanggal_berlaku' => '2025-01-01 00:00:00',
                'bidang_id' => 4,
                'jenis_id' => 2
            ],

            [
                'nama_sertifikasi' => 'Sertifikasi Web Developer',
                'tanggal' => now(),
                'tanggal_berlaku' => '2025-06-01 00:00:00',
                'bidang_id' => 7,
                'jenis_id' => 2
            ]
        ];

        DB::table('m_sertifikasi')->insert($data);
    }
}
