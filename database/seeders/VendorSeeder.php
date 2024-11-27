<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = 
        [
            [
                'vendor_id' => 1,
                'vendor_nama' => 'BM Solutions',
                'alamat' => 'Jalan Bisnis No. 1',
                'kota' => 'Jakarta',
                'no_telp' => '081234567890',
                'alamat_web' => 'https://www.bmsolutions.com',
            ],
            [
                'vendor_id' => 2,
                'vendor_nama' => 'BI Insights',
                'alamat' => 'Jalan Data No. 2',
                'kota' => 'Bandung',
                'no_telp' => '081234567891',
                'alamat_web' => 'https://www.biinsights.com',
            ],
            [
                'vendor_id' => 3,
                'vendor_nama' => 'DS Experts',
                'alamat' => 'Jalan Data Science No. 3',
                'kota' => 'Surabaya',
                'no_telp' => '081234567892',
                'alamat_web' => 'https://www.dsexperts.com',
            ],
            [
                'vendor_id' => 4,
                'vendor_nama' => 'DA Solutions',
                'alamat' => 'Jalan Analyst No. 4',
                'kota' => 'Yogyakarta',
                'no_telp' => '081234567893',
                'alamat_web' => 'https://www.dasolutions.com',
            ],
            [
                'vendor_id' => 5,
                'vendor_nama' => 'DE Hub',
                'alamat' => 'Jalan Engineering No. 5',
                'kota' => 'Medan',
                'no_telp' => '081234567894',
                'alamat_web' => 'https://www.dehub.com',
            ],
            [
                'vendor_id' => 6,
                'vendor_nama' => 'SA Systems',
                'alamat' => 'Jalan Systems No. 6',
                'kota' => 'Makassar',
                'no_telp' => '081234567895',
                'alamat_web' => 'https://www.sasystems.com',
            ],
            [
                'vendor_id' => 7,
                'vendor_nama' => 'WD Studio',
                'alamat' => 'Jalan Web No. 7',
                'kota' => 'Denpasar',
                'no_telp' => '081234567896',
                'alamat_web' => 'https://www.wdstudio.com',
            ],
            [
                'vendor_id' => 8,
                'vendor_nama' => 'GD Creators',
                'alamat' => 'Jalan Game No. 8',
                'kota' => 'Malang',
                'no_telp' => '081234567897',
                'alamat_web' => 'https://www.gdcreators.com',
            ],
            [
                'vendor_id' => 9,
                'vendor_nama' => 'MD Apps',
                'alamat' => 'Jalan Mobile No. 9',
                'kota' => 'Bali',
                'no_telp' => '081234567898',
                'alamat_web' => 'https://www.mdapps.com',
            ],
            [
                'vendor_id' => 10,
                'vendor_nama' => 'PRG Solutions',
                'alamat' => 'Jalan Program No. 10',
                'kota' => 'Palembang',
                'no_telp' => '081234567899',
                'alamat_web' => 'https://www.prgsolutions.com',
            ],
            [
                'vendor_id' => 11,
                'vendor_nama' => 'ITG Experts',
                'alamat' => 'Jalan IT Governance No. 11',
                'kota' => 'Balikpapan',
                'no_telp' => '081234567800',
                'alamat_web' => 'https://www.itgexperts.com',
            ],
            [
                'vendor_id' => 12,
                'vendor_nama' => 'CYB Security',
                'alamat' => 'Jalan Cybersecurity No. 12',
                'kota' => 'Batam',
                'no_telp' => '081234567801',
                'alamat_web' => 'https://www.cybsecurity.com',
            ],
            [
                'vendor_id' => 13,
                'vendor_nama' => 'CC Cloud',
                'alamat' => 'Jalan Cloud No. 13',
                'kota' => 'Solo',
                'no_telp' => '081234567802',
                'alamat_web' => 'https://www.cccloud.com',
            ],
            [
                'vendor_id' => 14,
                'vendor_nama' => 'IOT Solutions',
                'alamat' => 'Jalan IoT No. 14',
                'kota' => 'Semarang',
                'no_telp' => '081234567803',
                'alamat_web' => 'https://www.iotsolutions.com',
            ],
            [
                'vendor_id' => 15,
                'vendor_nama' => 'UIUX Design Co.',
                'alamat' => 'Jalan UIUX No. 15',
                'kota' => 'Banjarmasin',
                'no_telp' => '081234567804',
                'alamat_web' => 'https://www.uiuxdesignco.com',
            ],
            [
                'vendor_id' => 16,
                'vendor_nama' => 'QA Hub',
                'alamat' => 'Jalan Quality Assurance No. 16',
                'kota' => 'Pontianak',
                'no_telp' => '081234567805',
                'alamat_web' => 'https://www.qahub.com',
            ],
            [
                'vendor_id' => 17,
                'vendor_nama' => 'ITS Support',
                'alamat' => 'Jalan IT Support No. 17',
                'kota' => 'Pekanbaru',
                'no_telp' => '081234567806',
                'alamat_web' => 'https://www.itssupport.com',
            ],
            [
                'vendor_id' => 18,
                'vendor_nama' => 'SWE Solutions',
                'alamat' => 'Jalan Software No. 18',
                'kota' => 'Manado',
                'no_telp' => '081234567807',
                'alamat_web' => 'https://www.swesolutions.com',
            ],
            [
                'vendor_id' => 19,
                'vendor_nama' => 'AI Innovators',
                'alamat' => 'Jalan AI No. 19',
                'kota' => 'Jayapura',
                'no_telp' => '081234567808',
                'alamat_web' => 'https://www.aiinnovators.com',
            ],
            [
                'vendor_id' => 20,
                'vendor_nama' => 'ML Experts',
                'alamat' => 'Jalan Machine Learning No. 20',
                'kota' => 'Kupang',
                'no_telp' => '081234567809',
                'alamat_web' => 'https://www.mlexperts.com',
            ],
        ];
        DB::table('m_vendor')->insert($data);
        
    }
}