@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/upload_sertifikasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_sertifikasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Serifikat</th>
                        <th>Nama Sertifikasi</th>
                        <th>Masa Berlaku</th>
                        <th>Nama Vendor</th>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function modalAction(url) {
        $('#myModal').load(url, function() {
            $(this).modal('show');
        });
    }

    var dataSertifikasi;

    $(document).ready(function() {
        dataSertifikasi = $('#table_sertifikasi').DataTable({ // Sesuaikan dengan ID tabel
    serverSide: true,
    ajax: {
        url: "{{ url('upload_sertifikasi/list') }}",
        type: "POST",
        data: function(d) {
            d._token = "{{ csrf_token() }}";
        }
    },
            columns: [
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "no_sertif",
                    name: "no_sertif"
                },
                {
                    data: "nama_sertif",
                    name: "nama_sertif"
                },
                {
                    data: "masa_berlaku",
                    name: "masa_berlaku"
                },
                {
                    data: "nama_vendor",
                    name: "nama_vendor"
                },
                {
                    data: "aksi",
                    name: "aksi",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Event handler untuk form import
        $('#formImport').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            handleImport(formData);
        });
    });

</script>
@endpush