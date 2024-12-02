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
                        <input type="text" name="vendor_nama" id="vendor_nama" class="form-control" 
                               value="{{ $vendor->vendor_nama }}" required>
                        <small id="error-vendor_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required>{{ $vendor->alamat }}</textarea>
                        <small id="error-alamat" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" name="kota" id="kota" class="form-control" 
                               value="{{ $vendor->kota }}" required>
                        <small id="error-kota" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control" 
                               value="{{ $vendor->no_telp }}" required>
                        <small id="error-no_telp" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Alamat Web</label>
                        <input type="url" name="alamat_web" id="alamat_web" class="form-control" 
                               value="{{ $vendor->alamat_web }}" required>
                        <small id="error-alamat_web" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                        minlength: 5,
                        maxlength: 255
                    },
                    kota: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    no_telp: {
                        required: true,
                        minlength: 10,
                        maxlength: 20
                    },
                    alamat_web: {
                        required: true,
                        url: true,
                        maxlength: 100
                    }
                },
                messages: {
                    vendor_nama: {
                        required: "Nama vendor harus diisi",
                        minlength: "Nama vendor minimal 3 karakter",
                        maxlength: "Nama vendor maksimal 100 karakter"
                    },
                    alamat: {
                        required: "Alamat harus diisi",
                        minlength: "Alamat minimal 5 karakter",
                        maxlength: "Alamat maksimal 255 karakter"
                    },
                    kota: {
                        required: "Kota harus diisi",
                        minlength: "Kota minimal 3 karakter",
                        maxlength: "Kota maksimal 100 karakter"
                    },
                    no_telp: {
                        required: "Nomor telepon harus diisi",
                        minlength: "Nomor telepon minimal 10 karakter",
                        maxlength: "Nomor telepon maksimal 20 karakter"
                    },
                    alamat_web: {
                        required: "Alamat web harus diisi",
                        url: "Format alamat web tidak valid",
                        maxlength: "Alamat web maksimal 100 karakter"
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
@endempty