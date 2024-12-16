<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload Surat Tugas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ url('/surat_tugas/upload_ajax') }}" method="POST" id="form-upload" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="kegiatan_id" value="{{ $kegiatan_id }}">
            <input type="hidden" name="jenis" value="{{ $jenis }}">
            
            <div class="modal-body">
                <div class="form-group">
                    <label>Format Surat</label>
                    <div>
                        <a href="{{ url('/surat_tugas/exportTemplate/' . $kegiatan_id . '?jenis=' . $jenis) }}" 
                           class="btn btn-info btn-sm">
                            <i class="fa fa-file-word"></i> Download Format
                        </a>
                    </div>
                    <small class="form-text text-muted">Download format surat terlebih dahulu</small>
                </div>

                <div class="form-group">
                    <label>Upload Surat <span class="text-danger">*</span></label>
                    <input type="file" name="file_surat" id="file_surat" class="form-control" required accept=".doc,.docx,.pdf">
                    <small class="text-muted">Format: .doc, .docx, .pdf (Max: 2MB)</small>
                    <small id="error-file_surat" class="error-text form-text text-danger"></small>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, 'Ukuran file melebihi batas yang diizinkan');

    $("#form-upload").validate({
        rules: {
            file_surat: {
                required: true,
                filesize: 2048000
            }
        },
        messages: {
            file_surat: {
                required: "File surat wajib diupload"
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            $('#myModal').modal('hide');
                            dataTable.ajax.reload();
                        });
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                        $.each(response.errors, function(key, value) {
                            $('#error-' + key).text(value);
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    Swal.fire('Error', 'Terjadi kesalahan sistem: ' + xhr.responseJSON.message, 'error');
                }
            });
            return false;
        }
    });
});
</script>