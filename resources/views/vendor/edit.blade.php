@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Vendor</h2>
    <form action="{{ route('vendor.update', $vendor->vendor_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama Vendor</label>
            <input type="text" name="vendor_nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Kota</label>
            <input type="text" name="kota" class="form-control" required>
        </div>
        <div class="form-group">
            <label>No. Telepon</label>
            <input type="text" name="no_telp" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alamat Website</label>
            <input type="text" name="alamat_web" class="form-control" required>
        </div>        
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
