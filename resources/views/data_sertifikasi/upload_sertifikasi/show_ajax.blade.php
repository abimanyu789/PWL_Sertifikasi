@empty($sertifikasi)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/upload_sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Sertifikat Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        @if($sertifikasi->bukti)
                            <div class="text-center">
                                @if(in_array(strtolower(pathinfo($sertifikasi->bukti, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ asset('storage/sertifikasi/' . $sertifikasi->bukti) }}" 
                                        alt="Bukti Sertifikat" 
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height: 300px;">
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-file-pdf"></i> Dokumen PDF tersedia.
                                        <a href="{{ asset('storage/sertifikasi/' . $sertifikasi->bukti) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-info ml-2">
                                            <i class="fas fa-external-link-alt"></i> Buka PDF
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle"></i> Bukti dokumen tidak tersedia
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th width="30%">Nomor Sertifikat</th>
                                <td>{{ $sertifikasi->no_sertif }}</td>
                            </tr>
                            <tr>
                                <th>Nama Sertifikasi</th>
                                <td>{{ $sertifikasi->nama_sertif }}</td>
                            </tr>
                            <tr>
                                <th>Jenis</th>
                                <td>{{ $sertifikasi->jenis->jenis_nama }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pelaksanaan</th>
                                <td>{{ date('d F Y', strtotime($sertifikasi->tanggal)) }}</td>
                            </tr>
                            <tr>
                                <th>Masa Berlaku</th>
                                <td>{{ date('d F Y', strtotime($sertifikasi->masa_berlaku)) }}</td>
                            </tr>
                            <tr>
                                <th>Nama Vendor</th>
                                <td>{{ $sertifikasi->nama_vendor }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Upload</th>
                                <td>{{ date('d F Y H:i:s', strtotime($sertifikasi->created_at)) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty