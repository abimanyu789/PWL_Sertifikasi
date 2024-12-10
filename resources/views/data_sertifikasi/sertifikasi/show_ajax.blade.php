@empty($sertifikasi)
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
                    Data yang anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty
