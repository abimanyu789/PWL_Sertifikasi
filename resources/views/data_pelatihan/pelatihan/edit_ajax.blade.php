@empty($pelatihan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/pelatihan/' . $pelatihan->pelatihan_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Pelatihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pelatihan</label>
                        <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" required>
                        <small id="error-nama_pelatihan" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" required></textarea>
                        <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kuota</label>
                            <div class="input-group">
                                <input type="number" name="kuota" class="form-control" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="tambahKuota()">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control" required>
                        <small id="error-lokasi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Biaya</label>
                            <input type="number" name="biaya" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Level Pelatihan</label>
                        <select name="level_pelatihan_id" id="level_pelatihan_id" class="form-control" required>
                            <option value="">- Pilih Level -</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                        <small id="error-level_pelatihan_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Vendor</label>
                        <select name="vendor_id" id="vendor_id" class="form-control" required>
                            <option value="">- Pilih Vendor -</option>
                            @foreach ($vendor as $v)
                                <option value="{{ $v->vendor_id }}">{{ $v->vendor_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-vendor_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jenis</label>
                        <select name="jenis_id" id="jenis_id" class="form-control" required>
                            <option value="">- Pilih Jenis -</option>
                            @foreach ($jenis as $j)
                                <option value="{{ $j->jenis_id }}">{{ $j->jenis_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-jenis_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Mata Kuliah</label>
                        <select name="mk_id" id="mk_id" class="form-control" required>
                            <option value="">- Pilih Mata Kuliah -</option>
                            @foreach ($matkul as $mk)
                                <option value="{{ $mk->mk_id }}">{{ $mk->mk_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-mk_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Periode</label>
                        <select name="periode_id" id="periode_id" class="form-control" required>
                            <option value="">- Pilih Periode -</option>
                            @foreach ($periode as $p)
                                <option value="{{ $p->periode_id }}">{{ $v->periode_tahun }}</option>
                            @endforeach
                        </select>
                        <small id="error-vendor_id" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
@endempty

<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                nama_pelatihan: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                deskripsi: {
                    required: true,
                    minlength: 3
                },
                tanggal: {
                    required: true,
                    date: true
                },
                bidang_id: {
                    required: true,
                    number: true
                },
                level_pelatihan_id: {
                    required: true,
                    number: true
                },
                vendor_id: {
                    required: true,
                    number: true
                },
                jenis_id: {
                    required: true,
                    number: true
                },
                mk_id: {
                    required: true,
                    number: true
                },
                periode_id: {
                    required: true,
                    number: true
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
                            dataPelatihan.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
