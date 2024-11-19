@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/vendor/import') }}')" class="btn btn-info">Import</button>
                <a href="{{ url('/vendor/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ url('/vendor/export_pdf') }}" class="btn btn-danger"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/vendor/create_ajax') }}')" class="btn btn-success">Tambah (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <table class="table table-bordered table-striped table-hover table-sm" id="table_vendor">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Vendor</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>No. Telepon</th>
                        <th>Website</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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

    var dataVendor;

    $(document).ready(function() {
        dataVendor = $('#table_vendor').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('vendor/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.jenis_id = $('#vendor_id').val();
                }
            },
            columns: [
                {
                    data: "vendor_id",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "vendor_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "alamat",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kota",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "no_telp",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "alamat_web",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#vendor_id').on('change', function() {
            dataVendor.ajax.reload();
        });
    });
</script>
@endpush
