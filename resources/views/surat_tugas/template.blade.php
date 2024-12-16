@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        @foreach($breadcrumb->list as $item)
                            <li class="breadcrumb-item">{{ $item }}</li>
                        @endforeach
                    </ol>
                </div>
                <h4 class="page-title">{{ $breadcrumb->title }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Form Upload Template -->
                    @if(auth()->user()->level_id == 1)
                    <div class="mb-4">
                        <h5>Upload Template Baru</h5>
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>File Template (.docx)</label>
                                <input type="file" name="file_template" class="form-control" accept=".docx" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Template</button>
                        </form>
                    </div>
                    <hr>
                    @endif

                    <!-- Download Template -->
                    <div>
                        <h5>Download Template</h5>
                        <p>Download template surat tugas yang tersedia.</p>
                        <a href="{{ route('surat-tugas.export-template') }}" class="btn btn-success">
                            <i class="mdi mdi-download mr-1"></i> Download Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->level_id == 1)
<script>
$(document).ready(function() {
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('surat-tugas.upload-template') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status) {
                    alert('Template berhasil diupload');
                    location.reload();
                } else {
                    alert('Gagal upload template: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endif
@endsection