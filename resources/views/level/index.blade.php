@extends('layouts.template') 
 
@section('content')  
    <div class="card">  
        <div class="card-header">  
            <h3 class="card-title">{{ $page->title }}</h3>  
            <div class="card-tools">  
<<<<<<< HEAD
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/level/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export (Excel)</a> 
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/level/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export (PDF)</a> 
=======
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/level/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Level</a> 
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/level/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Level</a> 
>>>>>>> 0f1a0778deebd95e558bae16a8bfcb49bb799121
            </div>  
        </div>  
        <div class="card-body">  
            @if(session('success'))  
                <div class="alert alert-success">{{ session('success') }}</div>  
            @endif  
            @if(session('error'))  
                <div class="alert alert-danger">{{ session('error') }}</div>  
            @endif  
 
            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">  
                <thead>  
                    <tr> 
                        <th>ID</th> 
                        <th>Kode Level</th> 
                        <th>Nama Level</th> 
                    </tr>  
                </thead>  
            </table>  
        </div>  
    </div>  
 
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>  
@endsection  
 
@push('js')  
<script>  
    function modalAction(url = ''){  
        $('#myModal').load(url, function(){  
            $('#myModal').modal('show');  
        });  
    }  
 
    var dataUser;  
    $(document).ready(function() {  
        dataUser = $('#table_user').DataTable({  
            processing: true,  
            serverSide: true,  
            ajax: {  
                "url": "{{ url('level/list') }}",  
                "dataType": "json",  
                "type": "POST",  
                "data": function (d) {  
                    d.level_id = $('#level_id').val();  
                }  
            },  
            columns: [{  
                    data: "DT_RowIndex",   
                    className: "text-center",  
                    orderable: false,  
                    searchable: false  
                },{  
                    data: "level_kode",   
                    orderable: true,  
                    searchable: true  
                },{  
                    data: "level_nama",   
                    orderable: true,  
                    searchable: true  
                }]  
        });  
 
        $('#level_id').on('change', function() {  
            dataUser.ajax.reload();  
        });  
    });  
</script>  
@endpush