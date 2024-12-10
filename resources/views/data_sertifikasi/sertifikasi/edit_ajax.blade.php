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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama Sertifikasi</label>
                                <input type="text" name="nama_sertifikasi" class="form-control" value="{{ $sertifikasi->nama_sertifikasi }}" required>
                                <small class="text-danger" id="error-nama_sertifikasi"></small>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="5" required>{{ $sertifikasi->deskripsi }}</textarea>
                                <small class="text-danger" id="error-deskripsi"></small>
                            </div>
                        </div>                    
    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ $sertifikasi->tanggal }}" required>
                                <small class="text-danger" id="error-tanggal"></small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kuota</label>
                                <div class="input-group">
                                    <input type="number" name="kuota" class="form-control" value="{{ $sertifikasi->kuota }}" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="tambahKuota()">+</button>
                                    </div>
                                </div>
                                <small class="text-danger" id="error-kuota"></small>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Level Sertifikasi</label>
                                <select name="level_sertifikasi" class="form-control" required>
                                    <option value="">Pilih Level</option>
                                    <option value="Profesi" {{ $sertifikasi->level_sertifikasi == 'Profesi' ? 'selected' : '' }}>Profesi</option>
                                    <option value="Keahlian" {{ $sertifikasi->level_sertifikasi == 'Keahlian' ? 'selected' : '' }}>Keahlian</option>
                                </select>
                                <small class="text-danger" id="error-level_sertifikasi"></small>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Vendor</label>
                                <select name="vendor_id" class="form-control" required>
                                    <option value="">Pilih Vendor</option>
                                    @foreach($vendor as $v)
                                    <option value="{{ $v->vendor_id }}" {{ $sertifikasi->vendor_id == $v->vendor_id ? 'selected' : '' }}>{{ $v->vendor_nama }}</option>
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
                                    <option value="{{ $j->jenis_id }}" {{ $sertifikasi->jenis_id == $j->jenis_id ? 'selected' : '' }}>{{ $j->jenis_nama }}</option>
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
                                    <option value="{{ $mk->mk_id }}" {{ $sertifikasi->mk_id == $mk->mk_id ? 'selected' : '' }}>{{ $mk->mk_nama }}</option>
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
                                    <option value="{{ $p->periode_id }}" {{ $sertifikasi->periode_id == $p->periode_id ? 'selected' : '' }}>{{ $p->periode_tahun }}</option>
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
            $("#form-edit").validate({
                rules: {
                    nama_sertifikasi: {
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
                    level_sertifikasi: {
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
                    return false;w
                }
            });
        });
        function tambahKuota() {
            let input = $('input[name="kuota"]');
            input.val(parseInt(input.val() || 0) + 1);
        }
    </script>
@endempty
