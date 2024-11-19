@empty($sertifikasi)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/sertifikasi/' . $sertifikasi->sertifikasi_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Sertifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Sertifikasi</label>
                        <input value="{{ $sertifikasi->nama_sertifikasi }}" type="text" name="nama_sertifikasi" id="nama_sertifikasi" class="form-control" required>
                        <small id="error-nama_sertifikasi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Sertifikasi</label>
                        <input value="{{ $sertifikasi->tanggal }}" type="date" name="tanggal" id="tanggal" class="form-control" required>
                        <small id="error-tanggal" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Bidang</label>
                        <select name="bidang_id" id="bidang_id" class="form-control" required>
                            <option value="">- Pilih Bidang -</option>
                            @foreach ($bidang as $b)
                                <option {{ $b->bidang_id == $sertifikasi->bidang_id ? 'selected' : '' }} value="{{ $b->bidang_id }}">{{ $b->nama_bidang }}</option>
                            @endforeach
                        </select>
                        <small id="error-bidang_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jenis Sertifikasi</label>
                        <select name="jenis_id" id="jenis_id" class="form-control" required>
                            <option value="">- Pilih Jenis Sertifikasi -</option>
                            @foreach ($jenis as $j)
                                <option {{ $j->jenis_id == $sertifikasi->jenis_id ? 'selected' : '' }} value="{{ $j->jenis_id }}">{{ $j->jenis_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-jenis_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Berlaku</label>
                        <input value="{{ $sertifikasi->tanggal_berlaku }}" type="date" name="tanggal_berlaku" id="tanggal_berlaku" class="form-control" required>
                        <small id="error-tanggal_berlaku" class="error-text form-text text-danger"></small>
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
            $("#form-edit").validate({
                rules: {
                    nama_sertifikasi: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    tanggal: {
                        required: true,
                        date: true
                    },
                    bidang_id: {
                        required: true,
                        number: true
                    },
                    jenis_id: {
                        required: true,
                        number: true
                    },
                    tanggal_berlaku: {
                        required: true,
                        date: true
                    }
                },
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
@endempty
