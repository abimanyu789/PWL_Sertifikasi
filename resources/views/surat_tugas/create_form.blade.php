<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Isi Form Berikut</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formSurat">
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis ?? '' }}">
                <input type="hidden" name="kegiatan_id" value="{{ isset($sertifikasi_id) ? $sertifikasi_id : $pelatihan_id }}">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="nama" value="{{ $user->nama }}" readonly>
                </div>
                <div class="form-group">
                    <label>NIP</label>
                    <input type="text" class="form-control" name="nip" value="{{ $user->nip }}" readonly>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" onclick="submitForm()">Buat Surat</button>
        </div>
    </div>
</div>

<script>
    function submitForm() {
        let formData = $('#formSurat').serialize();
        console.log('Form data yang akan dikirim:', formData); // Debug data form
        
        $.ajax({
            url: '{{ url("surat_tugas/create_form") }}',
            type: 'GET',
            data: formData,
            success: function(response) {
                console.log('Response dari server:', response); // Debug response
                
                if (response.status) {
                    $('#myModal').modal('hide');
                    dataValidasi.ajax.reload();
                    
                    let downloadUrl = '{{ url("surat_tugas/download") }}/' + response.surat_id;
                    console.log('URL download:', downloadUrl); // Debug URL
                    
                    // Buka PDF di tab baru
                    window.open(downloadUrl, '_blank');
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function(xhr) {
                console.error('Error response:', xhr.responseText); // Debug error
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            }
        });
    }
    </script>