<form action="{{ url('/pelatihan/import_ajax') }}" method="POST" id="form-import-pelatihan" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ url('/pelatihan/export_template') }}" class="btn btn-info btn-sm" download>
                        <i class="fa fa-file-excel"></i> Download Template
                    </a>
                </div>
                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file_pelatihan" id="file_pelatihan" class="form-control" required>
                    <small id="error-file_pelatihan" class="error-text form-text text-danger"></small>
                    <small class="form-text text-muted">File harus berformat .xlsx (Excel)</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-import-pelatihan").validate({
            rules: {
                file_pelatihan: {
                    required: true,
                    extension: "xlsx"
                }
            },
            messages: {
                file_pelatihan: {
                    required: "File harus dipilih",
                    extension: "File harus berformat .xlsx"
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: formData,  // Gunakan FormData
                    processData: false,  // Penting untuk file upload
                    contentType: false,  // Penting untuk file upload
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            dataPelatihan.ajax.reload();
                        } else {
                            if (response.msgField) {
                                $.each(response.msgField, function(field, message) {
                                    $('#error-' + field).text(message[0]);
                                });
                            }
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    }
                });
                return false;
            }
        });
    });
</script>
