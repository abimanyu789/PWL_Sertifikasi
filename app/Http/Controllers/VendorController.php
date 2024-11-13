<?php

namespace App\Http\Controllers;

use App\Models\MVendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // Menampilkan daftar vendor
    public function index()
    {
        $vendors = MVendor::all();
        return view('vendor.index', compact('vendors'));
    }

    // Menampilkan form tambah vendor
    public function create()
    {
        return view('vendor.create');
    }

    // Menyimpan data vendor baru
    public function store(Request $request)
    {
        $request->validate([
            'vendor_nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'alamat_web' => 'required|string|max:200',
        ]);
    
        MVendor::create([
            'vendor_nama' => $request->vendor_nama,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'no_telp' => $request->no_telp,
            'alamat_web' => $request->alamat_web,
        ]);
    
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan.');
    }    

    // Menampilkan detail vendor
    public function show($id)
    {
        $vendor = MVendor::findOrFail($id);
        return view('vendor.show', compact('vendor'));
    }

    // Menampilkan form edit vendor
    public function edit($id)
    {
        $vendor = MVendor::findOrFail($id);
        return view('vendor.edit', compact('vendor'));
    }

    // Mengupdate data vendor
    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'alamat_web' => 'required|string|max:200',
        ]);
    
        $vendor = MVendor::findOrFail($id);
        $vendor->update([
            'vendor_nama' => $request->vendor_nama,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'no_telp' => $request->no_telp,
            'alamat_web' => $request->alamat_web,
        ]);
    
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui.');
    }    

    // Menghapus data vendor
    public function destroy($id)
    {
        $vendor = MVendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus.');
    }
}
