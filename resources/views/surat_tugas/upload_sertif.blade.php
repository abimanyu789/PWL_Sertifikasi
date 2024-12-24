<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Sertifikat Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kegiatan_id" value="{{ $kegiatan_id }}">
                    <input type="hidden" name="nama_sertif" value="{{ $nama_kegiatan }}">
                    <input type="hidden" name="jenis_id" value="{{ $jenis_id }}">
                    <input type="hidden" name="nama_vendor" value="{{ $kegiatan->nama_vendor }}">
                    
                    <div class="form-group mb-3">
                        <label>Nama Sertifikat</label>
                        <input type="text" class="form-control" value="{{ $nama_kegiatan }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Jenis</label>
                        <input type="text" class="form-control" value="{{ ucfirst($jenis) }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Vendor</label>
                        <input type="text" class="form-control" value="{{ $kegiatan->nama_vendor }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Nomor Sertifikat</label>
                        <input type="text" class="form-control" name="no_sertif" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Tanggal Sertifikat</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Masa Berlaku</label>
                        <input type="date" class="form-control" name="masa_berlaku" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>File Sertifikat</label>
                        <input type="file" class="form-control" name="bukti" accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">Maksimal 2MB</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" form="uploadForm" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan di bawah form modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Tampilkan modal saat halaman dimuat
    $('#uploadModal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#uploadModal').modal('show');

    // Handle form submission
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]); 
        }
        
        $.ajax({
            url: '{{ url("/surat_tugas/sertif_ajax/" . $kegiatan_id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success:', response);  
                Swal.close();
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then((result) => {
                        window.location.href = '{{ url("/surat_tugas") }}';
                    });
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseJSON);
                Swal.close();
                Swal.fire('Error', 'Terjadi kesalahan saat upload', 'error');
            }
        });
    });
});
</script>