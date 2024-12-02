@extends('layouts.template')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10"> <!-- Mengurangi ukuran kolom agar lebih fokus -->
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

                    <!-- Bagian Foto Profil -->
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

<script>
    document.getElementById('avatar').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Belum ada file yang dipilih';
        document.getElementById('file-name').textContent = fileName;
    });
</script>

@endsection