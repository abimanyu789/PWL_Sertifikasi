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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama Pelatihan</label>
                                <input type="text" name="nama_pelatihan" class="form-control" value="{{ $pelatihan->nama_pelatihan }}" required>

                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="5" required>{{ $pelatihan->deskripsi }}</textarea>
                            </div>
                        </div>                    
    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ $pelatihan->tanggal }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kuota</label>
                                <div class="input-group">
                                    <input type="number" name="kuota" class="form-control" value="{{ $pelatihan->kuota }}" required>
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
                                <input type="text" name="lokasi" class="form-control" value="{{ $pelatihan->lokasi }}" required>
                                <small class="text-danger" id="error-lokasi"></small>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Biaya</label>
                                <input type="text" name="biaya" class="form-control" value="{{ $pelatihan->biaya }}" required>
                                <small class="text-danger" id="error-biaya"></small>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Level Pelatihan</label>
                                <select name="level_pelatihan" class="form-control" required>
                                    <option value="">Pilih Level</option>
                                    <option value="Nasional" {{ $pelatihan->level_pelatihan == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                    <option value="Internasional" {{ $pelatihan->level_pelatihan == 'Internasional' ? 'selected' : '' }}>Internasional</option>
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
                                        <option value="{{ $v->vendor_id }}" {{ $pelatihan->vendor_id == $v->vendor_id ? 'selected' : '' }}>{{ $v->vendor_nama }}</option>
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
                                        <option value="{{ $j->jenis_id }}" {{ $pelatihan->jenis_id == $j->jenis_id ? 'selected' : '' }}>{{ $j->jenis_nama }}</option>
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
                                        <option value="{{ $mk->mk_id }}" {{ $pelatihan->mk_id == $mk->mk_id ? 'selected' : '' }}>{{ $mk->mk_nama }}</option>
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
                                        <option value="{{ $p->periode_id }}" {{ $pelatihan->periode_id == $p->periode_id ? 'selected' : '' }}>{{ $p->periode_tahun }}</option>
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
@endempty

<script>
    $(document).ready(function() {
        $("#form-edit").validate({
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
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            dataPelatihan.ajax.reload();
                        } else {
                            if (response.msgField) {
                                $('.text-danger').text(''); // Reset error messages
                                $.each(response.msgField, function(field, message) {
                                    $('#error-' + field).text(message[0]);
                                });
                            }
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
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
