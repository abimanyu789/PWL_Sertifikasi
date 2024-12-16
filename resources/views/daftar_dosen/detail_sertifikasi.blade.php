<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Sertifikat Sertifikasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if($sertifikasi->bukti)
                <div class="text-center mb-3">
                    @if(pathinfo($sertifikasi->bukti, PATHINFO_EXTENSION) == 'pdf')
                        <div class="alert alert-info p-2">
                            <i class="fas fa-file-pdf"></i> Dokumen PDF tersedia
                            <a href="{{ asset('storage/sertifikasi/'.$sertifikasi->bukti) }}" 
                               class="btn btn-info btn-sm ml-2" 
                               target="_blank">Buka PDF</a>
                        </div>
                    @else
                        <img src="{{ asset('storage/sertifikasi/'.$sertifikasi->bukti) }}" 
                             class="img-fluid rounded">
                    @endif
                </div>
            @endif

            <table class="table table-striped">
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
                    <td>{{ \Carbon\Carbon::parse($sertifikasi->tanggal_pelaksanaan)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Masa Berlaku</th>
                    <td>{{ \Carbon\Carbon::parse($sertifikasi->masa_berlaku)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Nama Vendor</th>
                    <td>{{ $sertifikasi->nama_vendor }}</td>
                </tr>
                <tr>
                    <th>Tanggal Upload</th>
                    <td>{{ \Carbon\Carbon::parse($sertifikasi->created_at)->format('d F Y H:i:s') }}</td>
                </tr>
            </table>
        </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" 
            onclick="modalAction('{{ url('/view_dosen/' . $sertifikasi->user_id . '/sertifikasi') }}')">
            Kembali ke Daftar
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
</div>
</div>