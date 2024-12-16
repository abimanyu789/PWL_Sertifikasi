@if(empty($sertifikasi))
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
                    Data pengajuan sertifikasi yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/surat_tugas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Rekap Pengajuan Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informasi sertifikasi -->
                <h6 class="mt-1">Detail Kegiatan</h6>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>Nama Sertifikasi</th>
                        <td>{{ $sertifikasi->nama_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $sertifikasi->deskripsi }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $sertifikasi->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Level sertifikasi</th>
                        <td>{{ $sertifikasi->level_sertifikasi}}</td>
                    </tr>
                    <tr>
                        <th>Vendor</th>
                        <td>{{ $sertifikasi->vendor->vendor_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td>{{ $sertifikasi->jenis->jenis_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td>{{ $sertifikasi->periode->periode_tahun ?? '-' }}</td>
                    </tr>
                </table>
                <!-- Informasi Peserta -->
                <h6 class="mt-4">Daftar Peserta</h6>
                <table class="table table-bordered table-striped table-hover table-sm">
                    @if(auth()->user()->level_id == 1)
                        <!-- Jika Admin: Tampilkan semua peserta -->
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Peserta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peserta as $key => $p)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $p->user->nama ?? 'Nama tidak tersedia' }}</td>
                                <td>{{ $p->user->nip ?? 'NIP tidak tersedia' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    @else
                        <!-- Jika Dosen: Tampilkan hanya info dirinya -->
                        <tr>
                            <th>NIP</th>
                            <td>{{ $peserta->user->nip ?? 'NIP tidak tersedia' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Peserta</th>
                            <td>{{ $peserta->user->nama ?? 'Nama tidak tersedia' }}</td>
                        </tr>
                    @endif
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
                                jenis: 'sertifikasi'  // atau sesuaikan dengan jenis yang sedang diproses
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
@endempty