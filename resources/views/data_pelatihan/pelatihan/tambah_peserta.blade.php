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
                                            @forelse($users as $index => $user)
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input user-checkbox" 
                                                                id="user{{ $user->user_id }}" 
                                                                name="user_ids[]" 
                                                                value="{{ $user->user_id }}">
                                                            <label class="custom-control-label" for="user{{ $user->user_id }}"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->nama }}</td>
                                                    <td>
                                                        @php
                                                            $bidangs = explode(',', $user->bidang_names ?? '');
                                                        @endphp
                                                        @foreach($bidangs as $bidang)
                                                            @if(!empty($bidang))
                                                                <span class="badge badge-info">{{ $bidang }}</span>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @php
                                                            $mks = explode(',', $user->mk_names ?? '');
                                                        @endphp
                                                        @foreach($mks as $mk)
                                                            @if(!empty($mk))
                                                                <span class="badge badge-secondary">{{ $mk }}</span>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">{{ $user->jumlah_pelatihan }}</td>
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
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
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
                $(".user-checkbox").prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            // Handle individual checkbox changes
            $(".user-checkbox").change(function() {
                updateSelectedCount();
                if (!$(this).prop('checked')) {
                    $("#checkAll").prop('checked', false);
                }
            });

            // Update selected count
            function updateSelectedCount() {
                const selectedCount = $(".user-checkbox:checked").length;
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
                
                const selectedCount = $(".user-checkbox:checked").length;
                if (selectedCount === 0) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Pilih minimal satu dosen',
                        icon: 'warning'
                    });
                    return;
                }

                const kuota = {{ $pelatihan->kuota }};
                if (selectedCount > kuota) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Jumlah peserta melebihi kuota',
                        icon: 'warning'
                    });
                    return;
                }

                // Show loading
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan sistem',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endempty