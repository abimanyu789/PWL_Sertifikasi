@extends('layouts.template')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white py-4 text-center">
                    <h4 class="mb-0">Update Profil</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Bagian Foto Profil di atas -->
                    <div class="text-center mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/photos/'.$user->avatar) }}" class="img-fluid rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('/public/img/pp.jpg') }}" class="img-fluid rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <h5 class="mt-3">{{ $user->nama }}</h5>
                        <p class="text-muted mb-0">{{ $user->username }}</p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('profile.update', $user->user_id) }}" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf

                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control shadow-sm @error('username') is-invalid @enderror" name="username" value="{{ $user->username }}" required placeholder="Masukkan username Anda">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nama" class="col-md-4 col-form-label text-md-end">{{ __('Nama') }}</label>
                            <div class="col-md-8">
                                <input id="nama" type="text" class="form-control shadow-sm @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $user->nama) }}" required placeholder="Masukkan nama lengkap Anda">
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if($user->level_id == 3)
                            <div class="row mb-3">
                                <label for="bidang_id" class="col-md-4 col-form-label text-md-end">{{ __('Bidang Keahlian') }}</label>
                                <div class="col-md-8">
                                    <select id="bidang_id" class="form-control select2 @error('bidang_id') is-invalid @enderror" 
                                        name="bidang_id[]" multiple>
                                        @foreach($bidang as $b)
                                            <option value="{{ $b->bidang_id }}" 
                                                {{ in_array($b->bidang_id, old('bidang_id', $user->bidang_id ? explode(',', $user->bidang_id) : [])) ? 'selected' : '' }}>
                                                [{{ $b->bidang_kode }}] {{ $b->bidang_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bidang_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mk_id" class="col-md-4 col-form-label text-md-end">{{ __('Mata Kuliah') }}</label>
                                <div class="col-md-8">
                                    <select id="mk_id" class="form-control select2 @error('mk_id') is-invalid @enderror" 
                                        name="mk_id[]" multiple>
                                        @foreach($matkul as $mk)
                                            <option value="{{ $mk->mk_id }}" 
                                                {{ in_array($mk->mk_id, old('mk_id', $user->mk_id ? explode(',', $user->mk_id) : [])) ? 'selected' : '' }}>
                                                [{{ $mk->mk_kode }}] {{ $mk->mk_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mk_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <label for="avatar" class="col-md-4 col-form-label text-md-end">{{ __('Foto Profil') }}</label>
                            <div class="col-md-8">
                                <div class="custom-file">
                                    <input type="file" class="form-control d-none" id="avatar" name="avatar">
                                    <label class="btn btn-primary shadow-sm w-100 text-start" for="avatar">
                                        <i class="bi bi-upload"></i> Pilih Foto
                                    </label>
                                    <div id="file-name" class="mt-2 text-muted">Belum ada file yang dipilih</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="old_password" class="col-md-4 col-form-label text-md-end">{{ __('Password Lama') }}</label>
                            <div class="col-md-8">
                                <input id="old_password" type="password" class="form-control shadow-sm @error('old_password') is-invalid @enderror" name="old_password" placeholder="Masukkan password lama">
                                @error('old_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password Baru') }}</label>
                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control shadow-sm @error('password') is-invalid @enderror" name="password" placeholder="Masukkan password baru">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Konfirmasi Password') }}</label>
                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control shadow-sm" name="password_confirmation" placeholder="Konfirmasi password baru">
                            </div>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary shadow px-4 py-2">
                                <i class="bi bi-save-fill"></i> {{ __('Simpan Perubahan') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: 1px solid #006fe6;
        color: #fff;
        padding: 2px 8px;
        padding-left: 25px;
        position: relative;
        margin: 2px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        margin-right: 0;
        padding: 0 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ff0000;
        background-color: transparent;
        cursor: pointer;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih beberapa opsi",
            allowClear: true,
            width: '100%'
        });
    });

    document.getElementById('avatar').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Belum ada file yang dipilih';
        document.getElementById('file-name').textContent = fileName;
    });
</script>
@endpush
@endsection