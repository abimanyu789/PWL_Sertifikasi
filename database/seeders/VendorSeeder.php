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
                'alamat' => 'One Microsoft Way',
                'kota' => 'Redmond',
                'no_telp' => '0895-4882-8080',
                'alamat_web' => 'learn.microsoft.com'
            ],
            [
                'vendor_nama' => 'Oracle University',
                'alamat' => '500 Oracle Parkway',
                'kota' => 'Redwood City',
                'no_telp' => '0857-0506-7000',
                'alamat_web' => 'education.oracle.com'
            ],
            [
                'vendor_nama' => 'Cisco Learning',
                'alamat' => '170 West Tasman Dr',
                'kota' => 'San Jose',
                'no_telp' => '0881-8526-4000',
                'alamat_web' => 'learning.cisco.com'
            ],
            [
                'vendor_nama' => 'AWS Training',
                'alamat' => '410 Terry Avenue North',
                'kota' => 'Seattle',
                'no_telp' => '0896-7266-1000',
                'alamat_web' => 'aws.training'
            ],
            [
                'vendor_nama' => 'Google Cloud Training',
                'alamat' => '1600 Amphitheatre Parkway',
                'kota' => 'Mountain View',
                'no_telp' => '0882-2253-0000',
                'alamat_web' => 'cloud.google.com/training'
            ],
            [
                'vendor_nama' => 'Red Hat Training',
                'alamat' => '100 East Davie Street',
                'kota' => 'Raleigh',
                'no_telp' => '0881-9754-3700',
                'alamat_web' => 'redhat.com/training'
            ],
            [
                'vendor_nama' => 'IBM Training',
                'alamat' => '1 New Orchard Road',
                'kota' => 'Armonk',
                'no_telp' => '0882-4499-1900',
                'alamat_web' => 'ibm.com/training'
            ],
            [
                'vendor_nama' => 'VMware Education',
                'alamat' => '3401 Hillview Avenue',
                'kota' => 'Palo Alto',
                'no_telp' => '0877-3486-9273',
                'alamat_web' => 'vmware.com/education'
            ],
            [
                'vendor_nama' => 'Salesforce Training',
                'alamat' => 'Salesforce Tower',
                'kota' => 'San Francisco',
                'no_telp' => '0857-0901-7000',
                'alamat_web' => 'trailhead.salesforce.com'
            ],
            [
                'vendor_nama' => 'SAP Training',
                'alamat' => '3999 West Chester Pike',
                'kota' => 'Newtown Square',
                'no_telp' => '0896-7661-1000',
                'alamat_web' => 'training.sap.com'
            ]

        ];

        DB::table('m_vendor')->insert($data);
    }
}