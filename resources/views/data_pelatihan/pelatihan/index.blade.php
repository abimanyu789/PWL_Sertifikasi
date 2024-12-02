@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/pelatihan/import') }}')" class="btn btn-sm btn-info mt-1">Import</button>
                <a class="btn btn-sm btn-primary mt-1" h href="{{ url('/pelatihan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export (Excel)</a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/pelatihan/export_pd') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
                <button onclick="modalAction('{{ url('/pelatihan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah</button>
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
                            <select class="form-control" id="level_pelatihan_id" name="level_pelatihan_id">
                                <option value="">- Semua -</option>
                                @foreach ($level_pelatihan as $item)
                                    <option value="{{ $item->level_pelatihan_id }}">{{ $item->level_pelatihan_nama }}</option>
                                @endforeach
                            </select>                            
                            <small class="form-text text-muted">Level Pelatihan</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_pelatihan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelatihan</th>
                        <th>Tanggal</th>
                        <th>Bidang</th>
                        <th>Level Pelatihan</th>
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
        $('#myModal').load(url,function() {
            $('#myModal').modal('show');
        });
    }

    function handleImport(formData) {
        $.ajax({
            url: "{{ url('pelatihan/import_ajax') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status) {
                    alert(response.message);
                    $('#myModal').modal('hide');
                    dataLevel_pelatihan.ajax.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }
    var dataPelatihan;

    $(document).ready(function() {
        dataPelatihan = $('#table_pelatihan').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('pelatihan/list') }}",
                "dataType": "json",
                "type": "POST",

                "data": function(d) {
                    d.level_pelatihan_id = $('#level_pelatihan_id').val();
                }
            },
            columns: [
                {
                    data: "level_pelatihan_id",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "nama_pelatihan",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tanggal",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    // mengambil data level hasil dari ORM berelasi
                    data: "bidang.bidang_nama",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "level_pelatihan.level_pelatihan_nama", // menampilkan nama level pelatihan jika ada relasi
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "",
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