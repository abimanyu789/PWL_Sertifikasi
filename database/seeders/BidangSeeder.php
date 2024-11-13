<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['bidang_kode' => 'BM', 'bidang_nama' => 'Business Management'],
            ['bidang_kode' => 'BI', 'bidang_nama' => 'Business Intelligence'],
            ['bidang_kode' => 'DS', 'bidang_nama' => 'Data Science'],
            ['bidang_kode' => 'DA', 'bidang_nama' => 'Data Analyst'],
            ['bidang_kode' => 'DE', 'bidang_nama' => 'Data Engineering'],
            ['bidang_kode' => 'SA', 'bidang_nama' => 'Systems Analysis'],
            ['bidang_kode' => 'WD', 'bidang_nama' => 'Web Development'],
            ['bidang_kode' => 'GD', 'bidang_nama' => 'Game Development'],
            ['bidang_kode' => 'MD', 'bidang_nama' => 'Mobile App Development'],
            ['bidang_kode' => 'PRG', 'bidang_nama' => 'Programmer'],
            ['bidang_kode' => 'ITG', 'bidang_nama' => 'IT Governance'],
            ['bidang_kode' => 'CYB', 'bidang_nama' => 'Cybersecurity'],
            ['bidang_kode' => 'CC', 'bidang_nama' => 'Cloud Computing'],
            ['bidang_kode' => 'IOT', 'bidang_nama' => 'Internet of Things'],
            ['bidang_kode' => 'UIUX', 'bidang_nama' => 'UI/UX Design'],
            ['bidang_kode' => 'QA', 'bidang_nama' => 'Quality Assurance'],
            ['bidang_kode' => 'ITS', 'bidang_nama' => 'IT Support'],
            ['bidang_kode' => 'SWE', 'bidang_nama' => 'Software Engineering'],
            ['bidang_kode' => 'AI', 'bidang_nama' => 'Artificial Intelligence'],
            ['bidang_kode' => 'ML', 'bidang_nama' => 'Machine Learning'],
        ];
        DB::table('m_bidang')->insert($data);
    }
}
