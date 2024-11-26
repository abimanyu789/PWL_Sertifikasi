<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'vendor_nama' => 'PT. Jaya',
                'alamat' => 'Jl. Raya Jaya No. 123',
                'kota' => 'Jakarta',
                'no_telp' => '021-1234567',
                'alamat_web' => 'www.ptjaya.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vendor_nama' => 'PT. Sukamaju',
                'alamat' => 'Jl. Sukamaju No. 456',
                'kota' => 'Bandung',
                'no_telp' => '022-7654321',
                'alamat_web' => 'www.ptsukamaju.com',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('m_vendor')->insert($data);
    }
}