<form action="{{ url('/bidang/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Bidang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Kode Bidang</label>
                            <input type="text" name="bidang_kode" class="form-control" required>
                            <small class="text-danger" id="error-bidang_kode"></small>
                        </div>
                    </div>                   
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Bidang</label>
                            <input type="text" name="bidang_nama" class="form-control" required>
                            <small class="text-danger" id="error-bidang_nama"></small>
                        </div>
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
                bidang_kode: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                bidang_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                jenis_id: {
                    required: true
                },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            dataBidang.ajax.reload(); // karena ini untuk tabel bidang
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