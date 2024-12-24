<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Upload Sertifikat Kegiatan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $kegiatan_id }}">
                <input type="hidden" name="jenis" value="{{ $jenis }}">
                
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" class="form-control" value="{{ $nama_kegiatan }}" readonly>
                </div>

                <div class="form-group">
                    <label>File Sertifikat Kegiatan</label>
                    <input type="file" class="form-control" name="bukti" accept=".pdf,.doc,.docx" required>
                    <small class="text-muted">Maksimal 2MB</small>
                </div>

                <div class="text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '{{ url("/surat_tugas/sertif_ajax/" . $kegiatan_id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then((result) => {
                        $('#myModal').modal('hide');
                        // Refresh DataTable jika ada
                        if (typeof dataTable !== 'undefined') {
                            dataTable.ajax.reload();
                        }
                    });
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Terjadi kesalahan saat upload', 'error');
            }
        });
    });
});
</script>