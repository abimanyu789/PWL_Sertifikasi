@empty($pelatihan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/upload_pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/upload_pelatihan/' . $pelatihan->upload_id . '/update_ajax') }}" method="POST" id="form-edit" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sertifikat Pelatihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Sertifikat</label>
                        <input type="text" name="nama_sertif" value="{{ $pelatihan->nama_sertif }}" class="form-control" required>
                        <small id="error-nama_sertif" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nomor Sertifikat</label>
                        <input type="text" name="no_sertif" value="{{ $pelatihan->no_sertif }}" class="form-control" required>
                        <small id="error-no_sertif" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $pelatihan->tanggal }}" class="form-control" required>
                        <small id="error-tanggal" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Masa Berlaku</label>
                        <input type="date" name="masa_berlaku" value="{{ $pelatihan->masa_berlaku }}" class="form-control">
                        <small id="error-masa_berlaku" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jenis</label>
                        <select name="jenis_id" class="form-control" required>
                            <option value="">Pilih Jenis</option>
                            @foreach($jenis as $j)
                                <option value="{{ $j->jenis_id }}" {{ $pelatihan->jenis_id == $j->jenis_id ? 'selected' : '' }}>
                                    {{ $j->jenis_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-jenis_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Vendor</label>
                        <input type="text" name="nama_vendor" value="{{ $pelatihan->nama_vendor }}" class="form-control" required>
                        <small id="error-nama_vendor" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Bukti</label>
                        <input type="file" name="bukti" class="form-control">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah file</small>
                        <small id="error-bukti" class="error-text form-text text-danger"></small>
                        @if($pelatihan->bukti)
                            <div class="mt-2">
                                <small>File saat ini: {{ $pelatihan->bukti }}</small>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
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
@endempty