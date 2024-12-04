@extends('layouts.template')

@section('content')  
    <div class="card">  
        <div class="card-header">  
            <h3 class="card-title">{{ $page->title }}</h3>  
            <div class="card-tools">  
            </div>  
        </div>  
        <div class="card-body">  
            @if(session('success'))  
                <div class="alert alert-success">{{ session('success') }}</div>  
            @endif  
            @if(session('error'))  
                <div class="alert alert-danger">{{ session('error') }}</div>  
            @endif  

            <!-- Tabel untuk menampilkan data Quota -->
            <table class="table table-bordered table-striped table-hover table-sm" id="table_quota">  
                <thead>  
                    <tr> 
                        <th>No</th> 
                        <th>Nama Pelatihan</th> 
                        <th>Total Kuota</th> 
                        <th>Aksi</th>
                    </tr>  
                </thead>  
            </table>  
        </div>  
    </div>  

    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>  
@endsection  

@push('js')  
<script>  
    function modalAction(url = '') {  
        $('#myModal').load(url, function() {  
            $('#myModal').modal('show');  
        });  
    }  

    var dataQuota;  
    $(document).ready(function() {  
        dataQuota = $('#table_quota').DataTable({  
            processing: true,  
            serverSide: true,  
            ajax: {
                url: "{{ url('quota/list') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" // Token CSRF
                },
                data: function(d) {
                    // Tambahkan filter jika diperlukan
                }
            },
            columns: [
                {  
                    data: "DT_RowIndex",   
                    className: "text-center",  
                    orderable: false,  
                    searchable: false  
                },
                {  
                    data: "nama_pelatihan",   
                    orderable: true,  
                    searchable: true  
                },
                {  
                    data: "total_quota",   
                    className: "text-center",  
                    orderable: true,  
                    searchable: false  
                },
                {  
                    data: "aksi",   
                    className: "text-center",  
                    orderable: false,  
                    searchable: false  
                }
            ]  
        });  
    });  
</script>  
@endpush
