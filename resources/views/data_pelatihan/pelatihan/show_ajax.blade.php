@empty($pelatihan)
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
                    Data pelatihan yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Rekap Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                        <td>{{ $pelatihan->kuota }}</td>
                    </tr>
                    <tr>
                        <th>lokasi</th>
                        <td>{{ $pelatihan->lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Biaya</th>
                        <td>{{ $pelatihan->biaya }}</td>
                    </tr>
                    <tr>
                        <th>Level Pelatihan</th>
                        <td>{{ $pelatihan->level_pelatihan }}</td>
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
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty
