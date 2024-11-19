@empty($vendor)
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
                    Data vendor yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/vendor') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Vendor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th class="width-30">ID Vendor</th>
                        <td>{{ $vendor->vendor_id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Vendor</th>
                        <td>{{ $vendor->vendor_nama }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $vendor->alamat }}</td>
                    </tr>
                    <tr>
                        <th>Kota</th>
                        <td>{{ $vendor->kota }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $vendor->no_telp }}</td>
                    </tr>
                    <tr>
                        <th>Alamat Web</th>
                        <td>
                            <a href="{{ $vendor->alamat_web }}" target="_blank" rel="noopener noreferrer">
                                {{ $vendor->alamat_web }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ \Carbon\Carbon::parse($vendor->created_at)->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diperbarui</th>
                        <td>{{ \Carbon\Carbon::parse($vendor->updated_at)->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Pelatihan</th>
                        <td>{{ $vendor->pelatihan_count ?? 0 }} Pelatihan</td>
                    </tr>
                </table>

                @if(isset($vendor->pelatihan) && $vendor->pelatihan->count() > 0)
                    <div class="mt-4">
                        <h6 class="font-weight-bold">Daftar Pelatihan yang Ditangani</h6>
                        <table class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelatihan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendor->pelatihan as $index => $pelatihan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pelatihan->nama_pelatihan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pelatihan->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $pelatihan->status ?? 'Aktif' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty