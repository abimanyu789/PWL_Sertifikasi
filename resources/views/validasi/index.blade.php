@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                {{-- <button onclick="modalAction('{{ url('/pelatihan/import') }}')" class="btn btn-sm btn-info mt-1">Import</button>
                <a class="btn btn-sm btn-primary mt-1" h href="{{ url('/pelatihan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export (Excel)</a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/pelatihan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
                <button onclick="modalAction('{{ url('/pelatihan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah</button> --}}
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        {{-- <label class="col-1 control-label col-form-label">Filter:</label>
                        {{-- <div class="col-3"> --}}
                            {{-- <select class="form-control" id="level_pelatihan" name="level_pelatihan">
                                <option value="">- Semua -</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                            </select>                             --}}
                            {{-- <small class="form-text text-muted">Level Pelatihan</small> --}}
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_validasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelatihan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal ACC</th>
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

    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var dataValidasi;

    $(document).ready(function() {
    dataValidasi = $('#table_validasi').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/acc_daftar/list') }}", // pastikan URL ini sesuai route
            type: "GET"
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex"
            },
            {
                data: "nama_pelatihan",
                name: "nama_pelatihan"
            },
            {
                data: "tanggal",
                name: "tanggal"
            },
            {
                data: "tanggal_acc",
                name: "tanggal_acc"
            },
            {
                data: "aksi",
                name: "aksi",
                orderable: false,
                searchable: false
            }
        ]
    });
});

    function validasiPeserta(url) {
        Swal.fire({
            title: 'Validasi Peserta',
            text: "Apakah Anda yakin ingin memvalidasi peserta ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, validasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "status": "Approved"
                    },
                    success: function (response) {
                        if (response.status) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            dataValidasi.ajax.reload();
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        })
    }
</script>
@endpush