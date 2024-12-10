<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['jenis_kode' => 'TI', 'jenis_nama' => 'Teknologi Informasi'],
            ['jenis_kode' => 'SE', 'jenis_nama' => 'Software Engineering'],
            ['jenis_kode' => 'DS', 'jenis_nama' => 'Data Science'],
            ['jenis_kode' => 'AI', 'jenis_nama' => 'Artificial Intelligence'],
            ['jenis_kode' => 'CS', 'jenis_nama' => 'Cyber Security'],
            ['jenis_kode' => 'NE', 'jenis_nama' => 'Network Engineering'],
            ['jenis_kode' => 'UI', 'jenis_nama' => 'UI/UX Design'],
            ['jenis_kode' => 'DB', 'jenis_nama' => 'Database Systems'],
            ['jenis_kode' => 'ML', 'jenis_nama' => 'Machine Learning'],
            ['jenis_kode' => 'CC', 'jenis_nama' => 'Cloud Computing']
        ];

        DB::table('m_jenis')->insert($data);
    }
}
