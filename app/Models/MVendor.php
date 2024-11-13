<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MVendor extends Model
{
    use HasFactory;

    protected $table = 'm_vendor';
    protected $primaryKey = 'vendor_id';
    protected $fillable = ['vendor_nama', 'alamat', 'kota', 'no_telp', 'alamat_web'];
}
