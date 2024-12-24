{{-- @extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                @if(auth()->user()->level_id == 1)
                    <a href="{{ url('/surat_tugas/export_template') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                @endif
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
                        <label class="col-1 control-label col-form-label">Filter:</label>
                         <div class="col-3">
                            <select class="form-control" id="jenis_kegiatan" name="jenis_kegiatan">
                                <option value="">- Semua -</option>
                                <option value="pelatihan">Pelatihan</option>
                                <option value="sertifikasi">Sertifikasi</option>
                            </select>                             
                            <small class="form-text text-muted">Jenis Kegiatan</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_surat_tugas">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
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
    dataValidasi = $('#table_surat_tugas').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/surat_tugas/list') }}",
            type: "GET",
            data: function(d) {
                d.jenis_kegiatan = $('#jenis_kegiatan').val();
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false
            },
            {
                data: "nama_kegiatan",
                name: "nama_kegiatan"
            },
            {
                data: "aksi",
                name: "aksi",
                orderable: false,
                searchable: false
            }
        ],
        order: [[1, 'asc']]
    });

    $('#jenis_kegiatan').on('change', function() {
        dataValidasi.ajax.reload();
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
    });
}
</script>
@endpush --}}

@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                @if(auth()->user()->level_id == 1)
                    <a href="{{ url('/surat_tugas/export_template') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                @endif
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
                        <label class="col-1 control-label col-form-label">Filter:</label>
                         <div class="col-3">
                            <select class="form-control" id="jenis_kegiatan" name="jenis_kegiatan">
                                <option value="">- Semua -</option>
                                <option value="pelatihan">Pelatihan</option>
                                <option value="sertifikasi">Sertifikasi</option>
                            </select>                             
                            <small class="form-text text-muted">Jenis Kegiatan</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_surat_tugas">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Status</th>
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

// function modalAction(url = '') {
//     $('#myModal').load(url, function() {
//         $('#myModal').modal('show');
//     });
// }
function modalAction(url = '') {
    $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {
            $('#myModal').html(response).modal('show');
        },
        error: function(xhr) {
            console.error(xhr);
            Swal.fire('Error', 'Gagal memuat form', 'error');
        }
    });
}

var dataValidasi;

$(document).ready(function() {
    dataValidasi = $('#table_surat_tugas').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/surat_tugas/list') }}",
            type: "GET",
            data: function(d) {
                d.jenis_kegiatan = $('#jenis_kegiatan').val();
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false
            },
            {
                data: "nama_kegiatan",
                name: "nama_kegiatan"
            },
            {
                data: "status",
                name: "status",
                render: function(data, type, row) {
                    if (row.status === 'Approved') {
                        return `<span class='badge badge-success'>Approved</span>`;
                    } else if (row.status === 'Rejected') {
                        return `<span class='badge badge-danger'>Rejected</span>`;
                    } else {
                        return `<span class='badge badge-warning'>Pending</span>`;
                    }
                }
            },
             {
                data: "aksi",
                name: "aksi",
                orderable: false,
                searchable: false
            }
        ],
        order: [[1, 'asc']]
    });

    $('#jenis_kegiatan').on('change', function() {
        dataValidasi.ajax.reload();
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
            // Disable tombol agar tidak terkirim dua kali
            let btn = $('.swal2-confirm');
            btn.prop('disabled', true);

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "status": "Approved" // Ganti sesuai status
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
                },
                complete: function () {
                    // Enable kembali tombol jika perlu
                    btn.prop('disabled', false);
                }
            });
        }
    });
}

</script>
@endpush
