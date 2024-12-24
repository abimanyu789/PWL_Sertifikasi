<form action="{{ url('/surat_tugas/ajax') }}" method="POST" id="form-upload-bukti">
    @csrf
    <input type="hidden" name="jenis_kegiatan" value="{{ $jenis_kegiatan }}">
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Bukti {{ ucfirst($jenis_kegiatan) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" class="form-control" value="{{ $nama_kegiatan }}" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Sertifikat</label>
                    <input type="text" name="nama_sertif" id="nama_sertif" class="form-control" required>
                    <small id="error-nama_sertif" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor Sertifikat</label>
                    <input type="text" name="no_sertif" id="no_sertif" class="form-control" required>
                    <small id="error-no_sertif" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    <small id="error-tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Masa Berlaku</label>
                    <input type="date" name="masa_berlaku" id="masa_berlaku" class="form-control" required>
                    <small id="error-masa_berlaku" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis_id" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        @foreach($jenis as $j)
                            <option value="{{ $j->jenis_id }}">{{ $j->jenis_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-jenis_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Vendor</label>
                    <input type="text" name="nama_vendor" id="nama_vendor" class="form-control" required>
                    <small id="error-nama_vendor" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bukti</label>
                    <input type="file" name="bukti" id="bukti" class="form-control" required>
                    <small id="error-bukti" class="error-text form-text text-danger"></small>
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
    $("#form-upload-bukti").validate({
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
            masa_berlaku: {
                required: true
            },
            jenis_id: {
                required: true
            },
            nama_vendor: {
                required: true
            },
            bukti: {
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
                        dataValidasi.ajax.reload();
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

// $(document).ready(function() {
//     $("#form-upload-bukti").on('submit', function(e) {
//         e.preventDefault();
        
//         // Reset error messages
//         $('.error-text').text('');
        
//         var formData = new FormData(this);
        
//         $.ajax({
//             url: $(this).attr('action'),
//             method: 'POST',
//             data: formData,
//             processData: false,
//             contentType: false,
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             success: function(response) {
//                 if (response.status) {
//                     $('#myModal').modal('hide');
//                     Swal.fire('Berhasil', response.message, 'success').then(() => {
//                         // Reload datatable
//                         if (typeof dataValidasi !== 'undefined') {
//                             dataValidasi.ajax.reload();
//                         }
//                     });
//                 } else {
//                     if (response.msgField) {
//                         $.each(response.msgField, function(field, message) {
//                             $('#error-' + field).text(message[0]);
//                         });
//                     }
//                     Swal.fire('Gagal', response.message, 'error');
//                 }
//             },
//             error: function(xhr) {
//                 console.error(xhr);
//                 Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
//             }
//         });
//     });

//     // Basic form validation
//     $('input[required], select[required]').on('change', function() {
//         if ($(this).val()) {
//             $(this).next('.error-text').text('');
//         }
//     });
// });
</script>