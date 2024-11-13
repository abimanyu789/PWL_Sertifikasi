@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Vendor</h2>
    <a href="{{ route('vendor.create') }}" class="btn btn-success mb-3">Tambah Vendor</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Vendor</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>No. Telepon</th>
                <th>Alamat Website</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendors as $vendor)
                <tr>
                    <td>{{ $vendor->vendor_id }}</td>
                    <td>{{ $vendor->vendor_nama }}</td>
                    <td>{{ $vendor->alamat }}</td>
                    <td>{{ $vendor->kota }}</td>
                    <td>{{ $vendor->no_telp }}</td>
                    <td>{{ $vendor->alamat_web }}</td>
                    <td>
                        <a href="{{ route('vendor.show', $vendor->vendor_id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ route('vendor.edit', $vendor->vendor_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('vendor.destroy', $vendor->vendor_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
