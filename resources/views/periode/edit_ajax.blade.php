@empty($periode)
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
                <a href="{{ url('/periode') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/periode/' . $periode->periode_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Periode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Data Periode</label>
                        <div class="input-group">
                            <input type="number" class="form-control year-start" 
                                   id="year_start"
                                   placeholder="2023" min="2000" max="2099"
                                   value="{{ '20' . substr($periode->periode_tahun, 0, 2) }}"
                                   required>
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text">/</span>
                            </div>
                            <input type="number" class="form-control year-end" 
                                   id="year_end"
                                   placeholder="2024" min="2000" max="2099"
                                   value="{{ '20' . substr($periode->periode_tahun, 2, 2) }}"
                                   readonly>
                            
                            <input type="hidden" name="periode_tahun" id="periode_tahun" 
                                   value="{{ $periode->periode_tahun }}">
                        </div>
                        <small class="text-muted">Format: XXXX/XXXX contoh: 2023/2024</small>
                        <small id="error-periode_tahun" class="error-text form-text text-danger"></small>
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
     $('#year_start').on('input', function() {
         let startYear = $(this).val();
         if(startYear && startYear.length === 4) {
             // Set tahun akhir otomatis
             let endYear = parseInt(startYear) + 1;
             $('#year_end').val(endYear);
             
             // Format untuk database (2324)
             $('#periode_tahun').val(startYear.substr(2) + endYear.toString().substr(2));
         } else {
             $('#year_end').val('');
             $('#periode_tahun').val('');
         }
     });
     
     $("#form-tambah").validate({
         rules: {
             periode_tahun: {
                 required: true,
                 minlength: 4,
                 maxlength: 4
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
                         $('#table-periode').DataTable().ajax.reload();
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