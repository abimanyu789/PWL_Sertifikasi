@empty($daftar_dosen)
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
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    {{-- <tr>
                        <th width="30%">ID User</th>
                        <td>{{ $daftar_dosen->user_id }}</td>
                    </tr> --}}
                    <tr>
                        <th>Level</th>
                        <td>{{ $daftar_dosen->level->level_nama }}</td>
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td>{{ $daftar_dosen->nip }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $daftar_dosen->nama }}</td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $daftar_dosen->username }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $daftar_dosen->email }}</td>
                    </tr>
                    <tr>
                        <th>Bidang</th>
                        <td>
                            @if($daftar_dosen->bidang_id)
                                @php
                                    $bidangIds = explode(',', $daftar_dosen->bidang_id);
                                    $bidangNames = \App\Models\BidangModel::whereIn('bidang_id', $bidangIds)
                                        ->pluck('bidang_nama')
                                        ->join(', ');
                                @endphp
                                {{ $bidangNames ?: 'Belum memilih bidang' }}
                            @else
                                Belum memilih bidang
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Mata Kuliah</th>
                        <td>
                            @if($daftar_dosen->mk_id)
                                @php
                                    $mkIds = explode(',', $daftar_dosen->mk_id);
                                    $mkNames = \App\Models\MatkulModel::whereIn('mk_id', $mkIds)
                                        ->pluck('mk_nama')
                                        ->join(', ');
                                @endphp
                                {{ $mkNames ?: 'Belum memilih mata kuliah' }}
                            @else
                                Belum memilih mata kuliah
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
@endempty