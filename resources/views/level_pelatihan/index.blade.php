@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-sm btn-info mt-1">Import User</button>
                <a class="btn btn-sm btn-primary mt-1" h href="{{ url('/user/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export User</a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/user/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export User</a>
                <button onclick="modalAction('{{ url('user/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_level_pelatihan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Level Pelatihan</th>
                        <th>Nama Level Pelatihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataLevel_pelatihan;
        $(document).ready(function() {
            dataLevel_pelatihan = $('#table_level_pelatihan').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('level_pelatihan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.level_pelatihan_id = $('#level_pelatihan_id').val();
                    }
                },
                columns: [{
                    // nomor urut dari laravel datatable addIndexColumn()
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "level_pelatihan_kode",
                    className: "",
                    // orderable: true, jika ingin kolom ini bisa diurutkan
                    orderable: true,
                    // searchable: true, jika ingin kolom ini bisa dicari
                    searchable: true
                }, {
                    data: "level_pelatihan_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }]
            });
        });
    </script>
@endpush