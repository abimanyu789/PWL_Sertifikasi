<form action="{{ url('/sertifikasi/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Sertifikasi</label>
                    <input type="text" name="nama_sertifikasi" class="form-control" required>
                    <small class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Sertifikasi</label>
                    <input type="date" name="tanggal" class="form-control" required>
                    <small class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bidang</label>
                    <select name="bidang_id" class="form-control" required>
                        <option value="">- Pilih Bidang -</option>
                        @foreach ($bidang as $item)
                            <option value="{{ $item->bidang_id }}">{{ $item->bidang_nama }}</option>
                        @endforeach
                    </select>
                    <small class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Sertifikasi</label>
                    <select name="jenis_id" class="form-control" required>
                        <option value="">- Pilih Jenis -</option>
                        @foreach ($jenis as $item)
                            <option value="{{ $item->jenis_id }}">{{ $item->jenis_nama }}</option>
                        @endforeach
                    </select>
                    <small class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Berlaku</label>
                    <input type="date" name="tanggal_berlaku" class="form-control" required>
                    <small class="error-text text-danger"></small>
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
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataSertifikasi.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            }
        });
    });
</script>
