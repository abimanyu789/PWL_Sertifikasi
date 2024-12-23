{{-- @if(empty($pelatihan))
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data pengajuan pelatihan yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/surat_tugas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Rekap Pengajuan Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informasi Pelatihan -->
                <h6 class="mt-1">Detail Kegiatan</h6>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>Nama Pelatihan</th>
                        <td>{{ $pelatihan->nama_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $pelatihan->deskripsi }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $pelatihan->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $pelatihan->lokasi}}</td>
                    </tr>
                    <tr>
                        <th>Level Pelatihan</th>
                        <td>{{ $pelatihan->level_pelatihan}}</td>
                    </tr>
                    <tr>
                        <th>Vendor</th>
                        <td>{{ $pelatihan->vendor->vendor_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td>{{ $pelatihan->jenis->jenis_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td>{{ $pelatihan->periode->periode_tahun ?? '-' }}</td>
                    </tr>
                </table>
               <!-- Informasi Peserta -->
<h6 class="mt-4">Daftar Peserta</h6>
<table class="table table-bordered table-striped table-hover table-sm">
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama Peserta</th>
        </tr>
    </thead>
    <tbody>
        @if(auth()->user()->level_id == 1)
            <!-- Untuk Admin: Tampilkan semua peserta -->
            @foreach($peserta as $key => $p)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $p->user->nip ?? 'NIP tidak tersedia' }}</td>
                <td>{{ $p->user->nama ?? 'Nama tidak tersedia' }}</td>
            </tr>
            @endforeach
        @else
            <!-- Untuk Dosen: Tampilkan data dirinya dalam format yang sama -->
            <tr>
                <td>1</td>
                <td>{{ $peserta->user->nip ?? 'NIP tidak tersedia' }}</td>
                <td>{{ $peserta->user->nama ?? 'Nama tidak tersedia' }}</td>
            </tr>
        @endif
    </tbody>
</table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>

    <script>
        function validasiPeserta(url, status) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin akan ${status === 'Approved' ? 'menyetujui' : 'menolak'} pengajuan ini?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: status === 'Approved' ? '#28a745' : '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: status === 'Approved' ? 'Ya, Setuju!' : 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: status,
                                jenis: 'pelatihan'  // atau sesuaikan dengan jenis yang sedang diproses
                            },
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                                        $('#myModal').modal('hide');
                                        dataValidasi.ajax.reload();
                                    });
                                } else {
                                    Swal.fire('Gagal', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                console.error('Error:', xhr); // Tambahkan untuk debug
                                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                            }
                        });
                    }
                });
            }
        </script>
@endempty --}}

@if(empty($pelatihan))
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data pengajuan pelatihan yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/surat_tugas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Rekap Pengajuan Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informasi Pelatihan -->
                <h6 class="mt-1">Detail Kegiatan</h6>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>Nama Pelatihan</th>
                        <td>{{ $pelatihan->nama_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $pelatihan->deskripsi }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $pelatihan->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $pelatihan->lokasi}}</td>
                    </tr>
                    <tr>
                        <th>Level Pelatihan</th>
                        <td>{{ $pelatihan->level_pelatihan}}</td>
                    </tr>
                    <tr>
                        <th>Vendor</th>
                        <td>{{ $pelatihan->vendor->vendor_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td>{{ $pelatihan->jenis->jenis_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td>{{ $pelatihan->periode->periode_tahun ?? '-' }}</td>
                    </tr>
                </table>
                <!-- Informasi Peserta -->
                <h6 class="mt-4">Daftar Peserta</h6>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama Peserta</th>
                            @if(auth()->user()->level_id == 3)
                            <th>Status</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(auth()->user()->level_id == 1)
                            <!-- Untuk Admin: Tampilkan semua peserta -->
                            @foreach($peserta as $key => $p)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $p->user->nip ?? 'NIP tidak tersedia' }}</td>
                                <td>{{ $p->user->nama ?? 'Nama tidak tersedia' }}</td>
                            </tr>
                            @endforeach
                        @else
                            <!-- Untuk Dosen: Tampilkan semua peserta dengan highlight diri sendiri -->
                            @foreach($peserta as $key => $p)
                            <tr @if($p->user->user_id == auth()->user()->user_id) class="table-info" @endif>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $p->user->nip ?? 'NIP tidak tersedia' }}</td>
                                <td>{{ $p->user->nama ?? 'Nama tidak tersedia' }}</td>
                                <td>
                                    @if($p->user->user_id == auth()->user()->user_id)
                                        <span class="badge badge-info">Anda</span>
                                    @else
                                        <span class="badge badge-secondary">Peserta Lain</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
@endif