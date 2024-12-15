<form action="{{ url('/upload_sertifikasi/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Sertifikat Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Sertifikat</label>
                    <input type="text" name="nama_sertif" id="nama_sertif" class="form-control" required>
                    <small id="error-nama_sertif" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor Sertifikat</label>
                    <input type="text" name="no_sertif" id="no_sertif" class="form-control" required>
                    <small id="error-no_sertif" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    <small id="error-tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Masa Berlaku</label>
                    <input type="date" name="masa_berlaku" id="masa_berlaku" class="form-control">
                    <small id="error-masa_berlaku" class="error-text form-text text-danger"></small>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis</label>
                        <select name="jenis_id" class="form-control" required>
                            <option value="">Pilih Jenis</option>
                            @foreach($jenis as $j)
                                <option value="{{ $j->jenis_id }}">{{ $j->jenis_nama }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger" id="error-jenis_id"></small>
                    </div>
                </div>
                <div class="form-group">
                    <label>Nama Vendor</label>
                    <input type="text" name="nama_vendor" id="nama_vendor" class="form-control" required>
                    <small id="error-nama_vendor" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bukti</label>
                    <input type="file" name="bukti" id="bukti" class="form-control">
                    <small id="error-bukti" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                nama_sertif: {
                    required: true,
                    maxlength: 255
                },
                no_sertif: {
                    required: true,
                    minlength: 3
                },
                tanggal: {
                    required: true
                },
                nama_vendor: {
                    required: true
                }
            },
            submitHandler: function(form) {
        var formData = new FormData(form);
        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    $('#myModal').modal('hide');
                    Swal.fire('Berhasil', response.message, 'success');
                    dataSertifikasi.ajax.reload();
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