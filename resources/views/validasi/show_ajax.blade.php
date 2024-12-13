@empty($peserta)
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
                <a href="{{ url('/acc_daftar') }}" class="btn btn-warning">Kembali</a>
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
                        <th>Kuota</th>
                        <td>{{ $pelatihan->kuota}}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $pelatihan->lokasi}}</td>
                    </tr>
                    <tr>
                        <th>Biaya</th>
                        <td>{{ number_format($pelatihan->biaya, 0, ',', '.') }}</td>
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
                        <th>Mata Kuliah</th>
                        <td>{{ $pelatihan->mata_kuliah->mk_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td>{{ $pelatihan->periode->periode_tahun ?? '-' }}</td>
                    </tr>
                </table>

                <!-- Daftar Peserta -->
                <h5>Daftar Peserta</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Peserta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peserta as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->user->nama }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="validasiPeserta('{{ url('/acc_daftar/' . $pelatihan->pelatihan_id . '/validasi') }}', 'Rejected')">Tidak Setuju</button>
                <button type="button" class="btn btn-success" onclick="validasiPeserta('{{ url('/acc_daftar/' . $pelatihan->pelatihan_id . '/validasi') }}', 'Approved')">Setuju</button>
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
                            status: status
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Berhasil!', 
                                    text: response.message, 
                                    icon: 'success'
                                }).then(() => {
                                    $('#modalAction').modal('hide');
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass('modal-open');
                                    dataValidasi.ajax.reload(null, false); // false agar tidak reset ke halaman pertama
                                });
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endempty