<style>
    .sidebar {
    background-color: #1F4C97 !important; /* Warna biru sesuai desain */
    color: white;
    min-height: 88vh; /* Mengatur agar sidebar menggunakan seluruh tinggi layar */
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
    {{-- <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ auth()->user()->profile_image ? asset('storage/photos/' . auth()->user()->profile_image) : asset('/public/img/pp.png') }}"
                 class="img-circle elevation-2" 
                 alt="User Image">
        </div>
        <div class="info">
            <a href="{{ url('/profile') }}" class="d-block text-white">{{ auth()->user()->username ?? 'Nama Pengguna' }}</a>
        </div>
    </div> --}}

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
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ url('/') }}" 
                    class="nav-link {{ $activeMenu == 'dashboard' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-[#34495E]' }}">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Data Pengguna Section -->
            <li class="nav-item has-treeview {{ in_array($activeMenu, ['user', 'level', 'bidang']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['user', 'level', 'bidang']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Data Pengguna
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/user') }}" 
                            class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Dosen & Pimpinan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/level') }}" 
                            class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Level</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/bidang') }}" 
                            class="nav-link {{ $activeMenu == 'bidang' ? 'active' : '' }}">
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
                    <p>
                        Data Pelatihan
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/pelatihan') }}" 
                            class="nav-link {{ $activeMenu == 'pelatihan' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pelatihan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/level_pelatihan') }}" 
                            class="nav-link {{ $activeMenu == 'level_pelatihan' ? 'active' : '' }}">
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
                    <p>
                        Data Sertifikasi
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/sertifikasi') }}" 
                            class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Sertifikasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/jenis_sertifikasi') }}" 
                            class="nav-link {{ $activeMenu == 'jenis_sertifikasi' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Jenis Sertifikasi</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Data Vendor Section -->
            <li class="nav-item">
                <a href="{{ url('/vendor') }}" 
                    class="nav-link {{ $activeMenu == 'vendor' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                    <i class="fas fa-building nav-icon"></i>
                    <p>Data Vendor</p>
                </a>
            </li>

            <!-- Kuota Kegiatan Section -->
            <li class="nav-item">
                <a href="{{ url('/quota') }}" 
                    class="nav-link {{ $activeMenu == 'quota' ? 'active bg-blue-600 text-white' : 'text-gray-300' }}">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Kuota Kegiatan</p>
                </a>
            </li>

            <!-- Laporan Section -->
            <li class="nav-item has-treeview {{ in_array($activeMenu, ['upload_sertifikasi', 'upload_pelatihan']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['upload_sertifikasi', 'upload_pelatihan']) ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                    <i class="fas fa-file-alt nav-icon"></i>
                    <p>Laporan</p>
                    <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/upload_sertifikasi') }}" 
                            class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Sertifikasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/upload_pelatihan') }}" 
                            class="nav-link {{ $activeMenu == 'jenis_sertifikasi' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pelatihan</p>
                        </a>
                    </li>
                </ul>
            </li>

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
