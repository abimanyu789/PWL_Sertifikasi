<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width:100%; 
            border-collapse: collapse;
        }
        td, th {
            padding: 4px 3px;
        }
        th{
            text-align: left;
        }
        .d-block{
            display: block;
        }
        img.image{
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .p-1{
            padding: 5px 1px 5px 1px;
        }
        .font-10{
            font-size: 10pt;
        }
        .font-11{
            font-size: 11pt;
        }
        .font-12{
            font-size: 12pt;
        }
        .font-13{
            font-size: 13pt;
        }
        .border-bottom-header{
            border-bottom: 1px solid;
        }
        .border-all, .border-all th, .border-all td{
            border: 1px solid;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ asset('logo_polinema.png') }}" alt="Logo Polinema" width="80" height="80"/>
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA VENDOR</h3>
    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Vendor</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>No. Telepon</th>
                <th>Alamat Web</th>
                <th>Tgl Dibuat</th>
                <th>Tgl Update</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendors as $vendor)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $vendor->vendor_nama }}</td>
                <td>{{ $vendor->alamat }}</td>
                <td>{{ $vendor->kota }}</td>
                <td>{{ $vendor->no_telp }}</td>
                <td>{{ $vendor->alamat_web }}</td>
                <td>{{ \Carbon\Carbon::parse($vendor->created_at)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($vendor->updated_at)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <table>
            <tr>
                <td width="70%"></td>
                <td width="30%" class="text-center">
                    <p>Malang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p>Mengetahui,</p>
                    <br><br><br>
                    <p>_______________________</p>
                    <p>NIP.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>