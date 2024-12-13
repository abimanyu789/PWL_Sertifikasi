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
                <a href="{{ url('/acc_daftar') }}" class="btn btn-warning">Kembali</a>
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
                        <th>Kuota</th>
                        <td>{{ $sertifikasi->kuota}}</td>
                    </tr>
                    <tr>
                        <th>Level Sertifikasi</th>
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
                        <th>Mata Kuliah</th>
                        <td>{{ $sertifikasi->mata_kuliah->mk_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td>{{ $sertifikasi->periode->periode_tahun ?? '-' }}</td>
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
                        @foreach($peserta_sertifikasi as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->user->nama }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="validasiPesertaSertifikasi('{{ url('/acc_daftar/' . $sertifikasi->sertifikasi_id . '/validasi') }}', 'Rejected')">Tidak Setuju</button>
                <button type="button" class="btn btn-success" onclick="validasiPesertaSertifikasi('{{ url('/acc_daftar/' . $sertifikasi->sertifikasi_id . '/validasi') }}', 'Approved')">Setuju</button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>

    <script>
        function validasiPesertaSertifikasi(url, status) {
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
                    kegiatan: 'sertifikasi'
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            title: 'Berhasil!', 
                            text: response.message, 
                            icon: 'success'
                        }).then(() => {
                            // Perbaikan disini
                            $('#myModal').modal('hide'); // Sesuaikan dengan ID modal yang benar
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            dataValidasi.ajax.reload(null, false);
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