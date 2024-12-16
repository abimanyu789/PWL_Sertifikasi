@extends('layouts.template')
<html> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <style> 
        body { 
            font-family: "Times New Roman", Times, serif;
            margin: 40px;
            line-height: 1.15;
        }
        /* Style untuk header/kop surat */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
        }
        .header-container:after {
            content: '';
            display: block;
            border-bottom: 1px solid black;
            margin-top: 4px;
        }
        .logo-container {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
        }
        .text-container {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .logo {
            width: 90px;
            height: 90px;
        }
        .institution-name {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
        }
        .institution-name-large {
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0;
        }
        .address {
            font-size: 11pt;
            margin: 2px 0;
        }
        .title {
            text-align: center;
            margin: 30px 0 20px;
        }
        .title h3 {
            font-size: 14pt;
            text-decoration: underline;
            margin: 0;
        }
        .content {
            margin: 20px 0;
        }
        table.data {
            margin: 10px 0 10px 30px;
            width: 100%;
        }
        table.data td {
            padding: 3px;
        }
        .signature {
            margin-top: 40px;
            text-align: left;
            float: right;
            width: 250px;
        }
        .signature p {
            margin: 3px 0;
        }
        /* Tabel Peserta */
        .peserta {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .peserta th, .peserta td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        /* Page Break */
        .page-break {
            page-break-before: always;
            padding-top: 40px;
        }
    </style> 
</head>
<body>
    <!-- Header/Kop Surat -->
    <div class="header-container">
        <div class="logo-container">
            <img src="{{ public_path('logo_polinema.png') }}" class="logo" alt="Logo Polinema">
        </div>
        <div class="text-container">
            <p class="institution-name">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</p>
            <p class="institution-name-large">POLITEKNIK NEGERI MALANG</p>
            <p class="address">Jl. Soekarno-Hatta No. 9 Malang 65141</p>
            <p class="address">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</p>
            <p class="address">Laman: www.polinema.ac.id</p>
        </div>
    </div>
    
    <!-- Judul Surat -->
    <div class="title">
        <h3>SURAT TUGAS</h3>
        <p>Nomor: </p>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>
        <table class="data">
            <tr>
                <td width="100">Nama</td>
                <td width="10">:</td>
                <td>Dr.Eng. Rosa Andrie Asmara, ST, MT</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>198010102005011001</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>Ketua Jurusan Teknologi Informasi</td>
            </tr>
        </table>

        <p>Dengan ini menugaskan kepada anggota pada lampiran. Untuk melaksanakan kegiatan sebagai berikut:</p>
        <table class="data">
            <tr>
                <td width="100">Nama Kegiatan : </td>
                <td width="10">:</td>
                
            </tr>
            <tr>
                <td>Hari/Tanggal  : </td>            
                
            </tr>
            <tr>
                <td>Tempat        : </td>
                
            </tr>
        </table>

        <p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>........., ................</p>
        <p>Ketua Jurusan Teknologi Informasi,</p>
        <br><br><br>
        <p><u>Dr.Eng. Rosa Andrie Asmara, ST, MT</u></p>
        <p>NIP. 198010102005011001</p>
    </div>

    <!-- Lampiran Daftar Peserta -->
    <div class="page-break">

        <!-- Header/Kop Surat -->
        <div class="header-container">
            <div class="logo-container">
                <img src="{{ asset('logo_polinema.png') }}" class="logo" alt="Logo Polinema">
            </div>
            <div class="text-container">
                <p class="institution-name">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</p>
                <p class="institution-name-large">POLITEKNIK NEGERI MALANG</p>
                <p class="address">Jl. Soekarno-Hatta No. 9 Malang 65141</p>
                <p class="address">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</p>
                <p class="address">Laman: www.polinema.ac.id</p>
            </div>
        </div>

        
        <h3 style="text-align: center; margin: 20px 0;">DAFTAR PESERTA</h3>
        <h3 style="text-align: center; margin: 20px 0;"></h3>

        <table class="peserta">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th width="200">NIP</th>
                    <th>NAMA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td> </td>
                    <td> </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>