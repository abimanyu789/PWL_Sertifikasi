<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['mk_kode' => 'MK001', 'mk_nama' => 'Pemrograman Dasar'],
            ['mk_kode' => 'MK002', 'mk_nama' => 'Algoritma dan Basis Data'],
            ['mk_kode' => 'MK003', 'mk_nama' => 'Analisis dan Perancangan Sistem'],
            ['mk_kode' => 'MK004', 'mk_nama' => 'Jaringan Komputer'],
            ['mk_kode' => 'MK005', 'mk_nama' => 'Kecerdasan Buatan'],
            ['mk_kode' => 'MK006', 'mk_nama' => 'Matematika Diskrit'],
            ['mk_kode' => 'MK007', 'mk_nama' => 'Pemrograman Mobile'],
            ['mk_kode' => 'MK008', 'mk_nama' => 'Rekayasa Perangkat Lunak'],
            ['mk_kode' => 'MK009', 'mk_nama' => 'Machine Learning'],
            ['mk_kode' => 'MK010', 'mk_nama' => 'Data Mining'],
        ];

        DB::table('m_mata_kuliah')->insert($data);
    }
}
