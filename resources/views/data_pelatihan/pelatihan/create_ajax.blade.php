<form action="{{ url('/pelatihan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Pelatihan</label>
                            <input type="text" name="nama_pelatihan" class="form-control" required>
                            <small class="text-danger" id="error-nama_pelatihan"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="5" required></textarea>
                            <small class="text-danger" id="error-deskripsi"></small>
                        </div>
                    </div>                    

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                            <small class="text-danger" id="error-tanggal"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kuota</label>
                            <div class="input-group">
                                <input type="number" name="kuota" class="form-control" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="tambahKuota()">+</button>
                                </div>
                            </div>
                            <small class="text-danger" id="error-kuota"></small>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required>
                            <small class="text-danger" id="error-lokasi"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Biaya</label>
                            <input type="text" name="biaya" class="form-control" required>
                            <small class="text-danger" id="error-biaya"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Level Pelatihan</label>
                            <select name="level_pelatihan" class="form-control" required>
                                <option value="">Pilih Level</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                            </select>
                            <small class="text-danger" id="error-level_pelatihan"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Vendor</label>
                            <select name="vendor_id" class="form-control" required>
                                <option value="">Pilih Vendor</option>
                                @foreach($vendor as $v)
                                    <option value="{{ $v->vendor_id }}">{{ $v->vendor_nama }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger" id="error-vendor_id"></small>
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

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mata Kuliah</label>
                            <select name="mk_id" class="form-control" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($mata_kuliah as $mk)
                                    <option value="{{ $mk->mk_id }}">{{ $mk->mk_nama }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger" id="error-mk_id"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Periode</label>
                            <select name="periode_id" class="form-control" required>
                                <option value="">Pilih Periode</option>
                                @foreach($periode as $p)
                                    <option value="{{ $p->periode_id }}">{{ $p->periode_tahun }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger" id="error-periode_id"></small>
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
                nama_pelatihan: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                deskripsi: {
                    required: false,
                    maxlength: 255
                },
                tanggal: {
                    required: true,
                    date: true
                },
                kuota: {
                    required: true,
                    digits: true,
                    min: 1
                },
                lokasi: {
                    required: true,
                    maxlength: 255
                },
                biaya: {
                    required: true,
                    digits: true,
                    min: 0
                },
                level_pelatihan: {
                    required: true
                },
                vendor_id: {
                    required: true
                },
                jenis_id: {
                    required: true
                },
                mk_id: {
                    required: true
                },
                periode_id: {
                    required: true
                }
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

    function tambahKuota() {
        let input = $('input[name="kuota"]');
        input.val(parseInt(input.val() || 0) + 1);
    }
</script>