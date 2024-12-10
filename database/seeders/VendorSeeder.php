<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // [
            //     'vendor_nama' => 'PT. Jaya',
            //     'alamat' => 'Jl. Raya Jaya No. 123',
            //     'kota' => 'Jakarta',
            //     'no_telp' => '021-1234567',
            //     'alamat_web' => 'www.ptjaya.com',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'vendor_nama' => 'PT. Sukamaju',
            //     'alamat' => 'Jl. Sukamaju No. 456',
            //     'kota' => 'Bandung',
            //     'no_telp' => '022-7654321',
            //     'alamat_web' => 'www.ptsukamaju.com',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ]
            [
                'vendor_nama' => 'Microsoft Learning',
                'alamat' => 'Jl. Jaya Baya',
                'kota' => 'Surabaya',
                'no_telp' => '0895-4882-8080',
                'alamat_web' => 'learn.microsoft.com'
            ],
            [
                'vendor_nama' => 'Oracle University',
                'alamat' => 'Jl. Keraton',
                'kota' => 'Yogyakarta',
                'no_telp' => '0857-0506-7000',
                'alamat_web' => 'education.oracle.com'
            ],
            [
                'vendor_nama' => 'Cisco Learning',
                'alamat' => 'Jl. Singa',
                'kota' => 'Malang',
                'no_telp' => '0881-8526-4000',
                'alamat_web' => 'learning.cisco.com'
            ],
            [
                'vendor_nama' => 'AWS Training',
                'alamat' => 'Jl. Merdeka No. 7',
                'kota' => 'Tangerang',
                'no_telp' => '0896-7266-1000',
                'alamat_web' => 'aws.training'
            ],
            [
                'vendor_nama' => 'Google Cloud Training',
                'alamat' => 'Jl. Kuala Lumpur',
                'kota' => 'Sidoarjo',
                'no_telp' => '0882-2253-0000',
                'alamat_web' => 'cloud.google.com/training'
            ],
            [
                'vendor_nama' => 'Red Hat Training',
                'alamat' => 'Jl. Adara No. 55',
                'kota' => 'Bogor',
                'no_telp' => '0881-9754-3700',
                'alamat_web' => 'redhat.com/training'
            ],
            [
                'vendor_nama' => 'IBM Training',
                'alamat' => 'Jl. Sewu No. 2',
                'kota' => 'Semarang',
                'no_telp' => '0882-4499-1900',
                'alamat_web' => 'ibm.com/training'
            ],
            [
                'vendor_nama' => 'VMware Education',
                'alamat' => 'Jl. Grigi',
                'kota' => 'Gresik',
                'no_telp' => '0877-3486-9273',
                'alamat_web' => 'vmware.com/education'
            ],
            [
                'vendor_nama' => 'Salesforce Training',
                'alamat' => 'Jl. Abidari',
                'kota' => 'Cirebon',
                'no_telp' => '0857-0901-7000',
                'alamat_web' => 'trailhead.salesforce.com'
            ],
            [
                'vendor_nama' => 'SAP Training',
                'alamat' => 'Jl. Bidadara',
                'kota' => 'Ngoro',
                'no_telp' => '0896-7661-1000',
                'alamat_web' => 'training.sap.com'
            ]

        ];

        DB::table('m_vendor')->insert($data);
    }
}