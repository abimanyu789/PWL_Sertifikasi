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
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data pelatihan tidak ditemukan.
                </div>
            </div>
        </div>
    </div>
@else
    @if($pelatihan->sisa_kuota <= 0)
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informasi Kuota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                        Kuota pelatihan sudah penuh. Tidak dapat menambahkan peserta baru.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    @else
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Peserta Pelatihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-peserta-pelatihan" action="{{ url('/pelatihan/' . $pelatihan->pelatihan_id . '/kirim') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Info Pelatihan -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Pelatihan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="130">Nama Pelatihan</th>
                                                <td>: {{ $pelatihan->nama_pelatihan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal</th>
                                                <td>: {{ \Carbon\Carbon::parse($pelatihan->tanggal)->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kuota</th>
                                                <td>: {{ $pelatihan->kuota }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="130">Jenis</th>
                                                <td>: {{ $pelatihan->jenis->jenis_nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Mata Kuliah</th>
                                                <td>: {{ $pelatihan->mata_kuliah->mk_nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Level</th>
                                                <td>: {{ $pelatihan->level_pelatihan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sisa Kuota</th>
                                                <td>: {{ $pelatihan->sisa_kuota }} dari {{ $pelatihan->kuota }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Dosen -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Daftar Dosen</h6>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkAll">
                                    <label class="custom-control-label" for="checkAll">Pilih Semua</label>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Nama Dosen</th>
                                                <th>Bidang</th>
                                                <th>Mata Kuliah</th>
                                                <th class="text-center">Jumlah Pelatihan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($dosen as $index => $d)
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input dosen-checkbox" 
                                                                id="dosen{{ $d->dosen_id }}" name="user_ids[]" 
                                                                value="{{ $d->user_id }}">
                                                            <label class="custom-control-label" for="dosen{{ $d->dosen_id }}"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $d->nama }}</td>
                                                    <td>{{ $d->bidang_nama }}</td>
                                                    <td>{{ $d->mata_kuliah }}</td>
                                                    <td class="text-center">{{ $d->jumlah_pelatihan }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Tidak ada dosen yang tersedia</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div id="error-message" class="alert alert-danger mt-3" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
@endif   
    <script>
        $(document).ready(function() {
            // Handle check all functionality
            $("#checkAll").change(function() {
                $(".dosen-checkbox").prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            // Handle individual checkbox changes
            $(".dosen-checkbox").change(function() {
                updateSelectedCount();
                if (!$(this).prop('checked')) {
                    $("#checkAll").prop('checked', false);
                }
            });

            // Update selected count
            function updateSelectedCount() {
                const selectedCount = $(".dosen-checkbox:checked").length;
                const kuota = {{ $pelatihan->kuota }};
                
                if (selectedCount > kuota) {
                    $("#error-message")
                        .html(`<strong>Peringatan!</strong> Jumlah peserta terpilih (${selectedCount}) melebihi kuota (${kuota}).`)
                        .show();
                } else {
                    $("#error-message").hide();
                }
            }

            // Form submission
            $("#form-peserta-pelatihan").submit(function(e) {
                e.preventDefault();
                
                const selectedCount = $(".dosen-checkbox:checked").length;
                if (selectedCount === 0) {
                    Swal.fire('Peringatan', 'Pilih minimal satu dosen', 'warning');
                    return;
                }

                const kuota = {{ $pelatihan->kuota }};
                if (selectedCount > kuota) {
                    Swal.fire('Peringatan', 'Jumlah peserta melebihi kuota', 'warning');
                    return;
                }

                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                allowOutsideClick: false
                            }).then((result) => {
                                // Tutup modal
                                $('#modalAction').modal('hide');
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                
                                // Refresh DataTable
                                $('.data-table').DataTable().ajax.reload(null, false);
                                
                                // Redirect ke halaman index setelah delay singkat
                                setTimeout(function() {
                                    window.location.href = "{{ url('/pelatihan') }}";
                                }, 500);
                            });
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    }
                });
            });
        });
    </script>
@endempty