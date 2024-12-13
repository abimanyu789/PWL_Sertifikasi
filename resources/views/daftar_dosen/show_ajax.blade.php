@if(empty($daftar_dosen))
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
                <a href="{{ url('/view_dosen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th class="text-right col-3">ID User:</th>
                        <td class="col-9">{{ $daftar_dosen->user_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">NIP:</th>
                        <td>{{ $daftar_dosen->nip }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Nama:</th>
                        <td>{{ $daftar_dosen->nama }}</td>
                    </tr>
                </table>

                <h5>Sertifikasi yang Dimiliki</h5>
                @if($daftar_dosen->sertifikat->isEmpty())
                    <p>Dosen ini belum memiliki sertifikat.</p>
                @else
                    <ul>
                        @foreach ($daftar_dosen->sertifikat as $sertifikat)
                            <li>{{ $sertifikat->nama_sertif }}</li>
                        @endforeach
                    </ul>
                @endif

                <h5>Pelatihan yang Dimiliki</h5>
                @if($daftar_dosen->pelatihan->isEmpty())
                    <p>Dosen ini belum mengikuti pelatihan.</p>
                @else
                    <ul>
                        @foreach ($daftar_dosen->pelatihan as $pelatihan)
                            <li>{{ $pelatihan->nama_pelatihan }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
@endif