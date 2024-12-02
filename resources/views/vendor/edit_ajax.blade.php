@empty($vendor)
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
                <a href="{{ url('/vendor') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/vendor/' . $vendor->vendor_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Vendor</label>
                        <input value="{{ $vendor->vendor_nama }}" type="text" name="vendor_nama" id="vendor_nama" class="form-control" required>
                        <small id="error-vendor_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input value="{{ $vendor->alamat }}" type="text" name="alamat" id="alamat" class="form-control" required>
                        <small id="error-alamat" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Kota</label>
                        <input value="{{ $vendor->kota }}" type="text" name="kota" id="kota" class="form-control" required>
                        <small id="error-kota" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input value="{{ $vendor->no_telp }}" type="text" name="no_telp" id="no_telp" class="form-control" required>
                        <small id="error-no_telp" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Website</label>
                        <input value="{{ $vendor->alamat_web }}" type="text" name="alamat_web" id="alamat_web" class="form-control" required>
                        <small id="error-alamat_web" class="error-text form-text text-danger"></small>
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
                    vendor_nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    alamat: {
                        required: true,
                        maxlength: 255
                    },
                    kota: {
                        required: true,
                        maxlength: 255
                    },
                    no_telp: {
                        required: true,
                        number: true
                    },
                    alamat_web: {
                        required: true,
                        maxlength: 255
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
                                dataVendor.ajax.reload();
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