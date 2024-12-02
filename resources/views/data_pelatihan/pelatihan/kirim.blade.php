@extends('layouts.template')

@section('content')
<div class="container">
    <h1>Daftar Pelatihan</h1>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pelatihan</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelatihan as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->nama_pelatihan }}</td>
                <td>{{ $p->deskripsi }}</td>
                <td>
                    <button class="btn btn-success btn-sm" onclick="showDosenLayak({{ $p->pelatihan_id }})">
                        Kirim
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal untuk menampilkan daftar dosen -->
<div class="modal fade" id="modalDosenLayak" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Daftar Dosen Layak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="listDosenLayak">
                    <!-- Data dosen akan dimuat di sini -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @section('scripts')
<script>
    function showDosenLayak(pelatihanId) {
        $.ajax({
            url: `/pelatihan/${pelatihanId}/kirim`,
            method: 'GET',
            success: function (response) {
                let dosenList = response.dosen_layak;
                let listItems = '';

                if (dosenList.length > 0) {
                    dosenList.forEach(function (dosen) {
                        listItems += `<li class="list-group-item">
                            ${dosen.nama_dosen} - Telah mengikuti ${dosen.pelatihan_count} pelatihan
                        </li>`;
                    });
                } else {
                    listItems = '<li class="list-group-item">Tidak ada dosen yang memenuhi kriteria.</li>';
                }

                $('#listDosenLayak').html(listItems);
            },
            error: function () {
                $('#listDosenLayak').html('<li class="list-group-item text-danger">Gagal memuat data.</li>');
            }
        });
    }
</script>
@endsection --}}
@section('scripts')
<script>
$(document).ready(function() {
    function showDosenLayak(pelatihanId) {
        $.ajax({
            url: `/pelatihan/${pelatihanId}/dosenLayak`,
            method: 'GET',
            success: function (response) {
                let dosenList = response.dosen_layak;
                let listItems = '';

                if (dosenList.length > 0) {
                    dosenList.forEach(function (dosen) {
                        listItems += `<li class="list-group-item">
                            ${dosen.nama_dosen} - Telah mengikuti ${dosen.pelatihan_count} pelatihan
                        </li>`;
                    });
                } else {
                    listItems = '<li class="list-group-item">Tidak ada dosen yang memenuhi kriteria.</li>';
                }

                $('#modalTitle').text(`Daftar Dosen Layak untuk Pelatihan`);
                $('#listDosenLayak').html(listItems);
                $('#modalDosenLayak').modal('show');
            },
            error: function () {
                $('#listDosenLayak').html('<li class="list-group-item text-danger">Gagal memuat data.</li>');
                $('#modalDosenLayak').modal('show');
            }
        });
    }
    
});
</script>
@endsection
