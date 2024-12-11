<style>
    .sidebar {
    background-color: #1F4C97 !important; /* Warna biru sesuai desain */
    color: white;
    min-height: 100vh !important; /* Mengubah dari 88vh menjadi 100vh untuk memenuhi seluruh tinggi layar */
        height: 100% !important; /* Menambahkan height 100% */
        position: fixed !important; /* Menambahkan position fixed */
        left: 0;
        top: 0;
        bottom: 0;
        width: 250px; /* Sesuaikan lebar sidebar sesuai kebutuhan */
}

.nav-link {
    color: #d1d5db; /* Warna abu-abu */
}

.nav-link.active, .nav-link:hover {
    background-color: #4270BD !important; /* Menambahkan warna aktif/hijau */
    color: #fff !important;
}

/* Styling sidebar search */
.sidebar-search {
    padding: 10px;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
    border-radius: 8px; /* Membuat sudut melengkung seluruh kotak */
    background-color: #3b5998; /* Warna biru */
}

.search-input {
    width: 100%;
    padding: 10px;
    padding-right: 35px; /* Memberikan ruang untuk ikon di sebelah kanan */
    background-color: #3b5998; /* Warna yang sama dengan kotak luar */
    border: none;
    color: #c0c0c0; /* Warna teks abu-abu */
    border-radius: 8px; /* Membuat sudut melengkung seluruh input */
}

.search-input:focus {
    outline: none;
}

.search-icon {
    position: absolute;
    right: 10px; /* Posisi ikon di sebelah kanan */
    color: #c0c0c0; /* Warna ikon abu-abu */
    pointer-events: none; /* Mencegah ikon bisa di-klik */
}
</style>

<div class="sidebar">
    <div class="sidebar">
        <!-- Logo JTI Polinema -->
        <div class="logo-header">
            <div class="header-content">
                <i class="fas fa-graduation-cap logo-icon"></i>
                <div class="header-text">
                    <h4>JTI Polinema</h4>
                    <span>Sistem Pendataan Sertifikasi JTI</span>
                </div>
            </div>
        </div>
        <style>
            .logo-header {
                padding: 20px;
                background-color: #1F4C97;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .header-content {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .logo-icon {
                font-size: 30px;
                color: white;
            }

            .header-text {
                display: flex;
                flex-direction: column;
            }

            .header-text h4 {
                color: white;
                font-weight: 700;
                margin: 0;
                font-size: 16px;
                line-height: 1.2;
                margin-top: 4px; /* Memberikan jarak antara title dan subtitle */
            }

            .header-text span {
                color: #d1d5db;
                font-size: 12px;
                font-weight: normal;
                line-height: 1.2;
                margin-top: 4px; /* Memberikan jarak antara title dan subtitle */
            }

            .user-panel {
                display: flex;
                flex-direction: column; /* Atur vertikal */
                align-items: center; /* Pusatkan horizontal */
                justify-content: center; /* Pusatkan vertikal */
            }

            .user-panel .profile-img {
                width: 80px; /* Penuhi kontainer */
                height: 80px; /* Penuhi kontainer */
                object-fit: cover; /* Gambar menyesuaikan */
                border-radius: 80px; /* Membuat gambar berbentuk lingkaran */

                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan */
            }

            /* Styling nama pengguna */
            .user-panel .info {
                margin-top: 10px; /* Jarak antara foto dan nama */
                font-size: 16px; /* Ukuran teks */
                font-weight: bold; /* Tebalkan teks */
                text-align: center; /* Teks rata tengah */
            }
        </style>

<div class="sidebar-profile">
    <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex flex-column align-items-center">
        <div class="image">
            <img src="{{ auth()->user()->avatar ? asset('storage/photos/' . auth()->user()->avatar) : asset('img/pp.jpg') }}"
                class="profile-img img-circle elevation-2"
                alt="User Image">
        </div>
        <div class="info mt-2">
            <a href="{{ url('/profile') }}" class="d-block text-white text-center">{{ auth()->user()->username }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Dashboard (Untuk semua user) -->
            <li class="nav-item">
                <a href="{{ url('/') }}"
                    class="nav-link {{ $activeMenu == 'dashboard' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-[#34495E]' }}">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            @if(auth()->user()->level_id == 1)
                <!-- Menu Admin - Tetap seperti yang sudah ada -->
                <li class="nav-item has-treeview {{ in_array($activeMenu, ['user', 'level']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($activeMenu, ['user', 'level']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pengguna<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Data Section -->
                <li class="nav-item has-treeview {{ in_array($activeMenu, ['pelatihan', 'sertifikasi', 'jenis', 'bidang', 'matkul', 'vendor', 'periode']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($activeMenu, ['pelatihan', 'sertifikasi', 'jenis', 'bidang', 'matkul', 'vendor', 'periode']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Manage<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/pelatihan') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pelatihan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sertifikasi</p>
                            </a>
                        </li>
                        <!-- Jenis -->
                        <li class="nav-item">
                            <a href="{{ url('/jenis') }}" class="nav-link {{ $activeMenu == 'data_jenis' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i> <!-- Ikon untuk bidang minat -->
                                <p>Data Jenis</p>
                            </a>
                        </li>

                        <!-- Bidang Minat -->
                        <li class="nav-item">
                            <a href="{{ url('/bidang') }}" class="nav-link {{ $activeMenu == 'bidang' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bidang Minat</p>
                            </a>
                        </li>

                        <!-- Mata Kuliah -->
                        <li class="nav-item">
                            <a href="{{ url('/matkul') }}" class="nav-link {{ $activeMenu == 'matkul' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mata Kuliah</p>
                            </a>
                        </li>

                        <!-- Vendor -->
                        <li class="nav-item">
                            <a href="{{ url('/vendor') }}" class="nav-link {{ $activeMenu == 'vendor' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vendor</p>
                            </a>
                        </li>

                        <!-- Periode -->
                        <li class="nav-item">
                            <a href="{{ url('/periode') }}" class="nav-link {{ $activeMenu == 'periode' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Periode</p>
                            </a>
                        </li>
                    </ul>
                </li>



                <!-- Laporan Section -->
                <li class="nav-item">
                    <a href="{{ url('/surat_tugas') }}" class="nav-link {{ $activeMenu == 'surat_tugas' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Draft Surat Tugas</p>
                    </a>
                </li>

            @elseif(auth()->user()->level_id == 2)
                <!-- Menu Pimpinan -->
                <li class="nav-item">
                    <a href="{{ url('/view_dosen') }}" class="nav-link {{ $activeMenu == 'view_dosen' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>Monitoring</p>
                    </a>
                </li>

                <!-- ACC Pengajuan -->
                <li class="nav-item">
                    <a href="{{ url('/acc_daftar') }}" class="nav-link {{ $activeMenu == 'acc_daftar' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-check-circle"></i>
                            <p>Validasi Pengajuan</p>
                        </a>
                    </li>
                </li>

                <!-- Data Sertifikasi Section -->
                <li class="nav-item">
                    <a href="{{ url('/statistik') }}" class="nav-link {{ $activeMenu == 'statistik' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>Statistik</p>
                        </a>
                    </li>
                </li>

            @elseif(auth()->user()->level_id == 3)
                <!-- Menu Dosen -->
                <!-- Daftar Manidir -->
                <li class="nav-item has-treeview {{ in_array($activeMenu, ['mandiri_sertifikasi', 'mandiri_pelatihan']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($activeMenu, ['mandiri_sertifikasi', 'mandiri_pelatihan']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="fas fa-pen nav-icon"></i>
                        <p>Upload Mandiri<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/mandiri_sertifikasi') }}" class="nav-link {{ $activeMenu == 'mandiri_sertifikasi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sertifikasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/mandiri_pelatihan') }}" class="nav-link {{ $activeMenu == 'mandiri_pelatihan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pelatihan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Data Pelatihan Section -->
                <li class="nav-item">
                    <a href="{{ url('/list_pelatihan') }}" class="nav-link {{ $activeMenu == 'list_pelatihan' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Draf Surat Tugas </p>
                    </a>
                </li>
            @endif

            <!-- Logout Button -->
            <li class="nav-item mt-4">
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-block bg-gradient-danger text-white">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</div>
