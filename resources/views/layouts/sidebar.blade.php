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

    <!-- Sidebar Search Form -->
    <div class="sidebar-search">
        <div class="input-group">
            <input type="text" class="form-control search-input" placeholder="Search">
            <i class="fas fa-search search-icon"></i>
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
                <li class="nav-item has-treeview {{ in_array($activeMenu, ['user', 'level', 'bidang']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($activeMenu, ['user', 'level', 'bidang']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pengguna<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/bidang') }}" class="nav-link {{ $activeMenu == 'bidang' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bidang Minat</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Data Pelatihan Section -->
                <li class="nav-item has-treeview {{ in_array($activeMenu, ['pelatihan', 'level_pelatihan']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($activeMenu, ['pelatihan', 'level_pelatihan']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Data Pelatihan<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/pelatihan') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pelatihan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/level_pelatihan') }}" class="nav-link {{ $activeMenu == 'level_pelatihan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level Pelatihan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Data Sertifikasi Section -->
                <li class="nav-item has-treeview {{ in_array($activeMenu, ['sertifikasi', 'jenis_sertifikasi']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($activeMenu, ['sertifikasi', 'jenis_sertifikasi']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-certificate"></i>
                        <p>Data Sertifikasi<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sertifikasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/jenis_sertifikasi') }}" class="nav-link {{ $activeMenu == 'jenis_sertifikasi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jenis Sertifikasi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Data Vendor Section -->
                <li class="nav-item {{ in_array($activeMenu, ['vendor']) ? 'menu-open' : '' }}">
                    <a href="{{ url('/vendor') }}" class="nav-link {{ in_array($activeMenu, ['vendor']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="fas fa-building nav-icon"></i>
                        <p>Data Vendor<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/vendor') }}" class="nav-link {{ $activeMenu == 'vendor' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vendor</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Kuota Kegiatan Section -->
                <li class="nav-item">
                    <a href="{{ url('/quota') }}" class="nav-link {{ $activeMenu == 'quota' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Kuota Kegiatan</p>
                    </a>
                </li>

                <!-- Laporan Section -->
                <li class="nav-item has-treeview {{ in_array($activeMenu, ['upload_sertifikasi', 'upload_pelatihan']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($activeMenu, ['upload_sertifikasi', 'upload_pelatihan']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <p>Laporan<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/laporan_sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sertifikasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/laporan_pelatihan') }}" class="nav-link {{ $activeMenu == 'jenis_sertifikasi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pelatihan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/list_dosen') }}" class="nav-link {{ $activeMenu == 'list_dosen' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List Dosen</p>
                            </a>
                        </li>
                    </ul>
                </li>

            @elseif(auth()->user()->level_id == 2)
                <!-- Menu Pimpinan -->
                <li class="nav-item">
                    <a href="{{ url('/view_dosen') }}" class="nav-link {{ $activeMenu == 'view_dosen' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Daftar Dosen</p>
                    </a>
                </li>

                <!-- Data Pelatihan Section -->
                <li class="nav-item">
                    <a href="{{ url('/pelatihan') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Daftar Pelatihan</p>
                    </a>
                </li>

                <!-- Data Sertifikasi Section -->
                <li class="nav-item">
                    <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-certificate"></i>
                            <p>Sertifikasi</p>
                        </a>
                    </li>
                </li>

            @elseif(auth()->user()->level_id == 3)
                <!-- Menu Dosen -->
                <!-- Data Pelatihan Section -->
                <li class="nav-item">
                    <a href="{{ url('/pelatihan') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Data Pelatihan</p>
                    </a>
                </li>

                <!-- Data Sertifikasi Section -->
                <li class="nav-item">
                    <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-certificate"></i>
                            <p>Sertifikasi</p>
                        </a>
                    </li>
                </li>
                
                <li class="nav-item">
                    <a href="{{ url('/sertifikasi-saya') }}" class="nav-link {{ $activeMenu == 'sertifikasi_saya' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="fas fa-certificate nav-icon"></i>
                        <p>Sertifikasi Saya</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ url('/pelatihan-saya') }}" class="nav-link {{ $activeMenu == 'pelatihan_saya' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="fas fa-book nav-icon"></i>
                        <p>Pelatihan Saya</p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ in_array($activeMenu, ['upload_sertifikasi', 'upload_pelatihan']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($activeMenu, ['upload_sertifikasi', 'upload_pelatihan']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <p>Form Upload<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/upload_sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sertifikasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/upload_pelatihan') }}" class="nav-link {{ $activeMenu == 'jenis_sertifikasi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pelatihan</p>
                            </a>
                        </li>
                    </ul>
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