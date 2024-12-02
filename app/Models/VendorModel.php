<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class VendorModel extends Model
{
    protected $table = 'm_vendor';
    protected $primaryKey = 'vendor_id';
    
    // Sesuaikan dengan nama kolom di database
    protected $fillable = [
        'vendor_nama',
        'alamat',
        'kota',
        'no_telp',
        'alamat_web'
    ];
}