@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                {{-- <button onclick="modalAction('{{ url('/view_dosen/import') }}')" class="btn btn-sm btn-info mt-1">Import</button> --}}
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/view_dosen/export_excel') }}"><i class="fa fa-file-excel"></i> Export (Excel)</a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/view_dosen/export_pdf') }}"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
                {{-- <button onclick="modalAction('{{ url('view_dosen/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah</button> --}}
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="level_id" name="level_id">
                                <option value="">- Semua Level -</option>
                                @foreach ($level as $item)
                                    <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Level Pengguna</small>
                        </div>
                    </div>
                </div>
            </div> --}}

            <table class="table table-bordered table-striped table-hover table-sm" id="table_daftar_dosen">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
        data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataDaftarDosen;
        $(document).ready(function() {
            dataDaftarDosen = $('#table_daftar_dosen').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('view_dosen/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.level_id = $('#user_id').val();
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "nip",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "nama",
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

            $('#user_id').on('change', function() {
                dataDaftarDosen.ajax.reload();
            });
        });
    </script>
@endpush