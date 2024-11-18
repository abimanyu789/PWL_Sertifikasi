<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorModel extends Model
{
    protected $table = 'm_vendor';
    protected $primaryKey = 'vendor_id';
    protected $fillable = [
        'vendor_nama',
        'alamat',
        'kota',
        'alamat_web'
    ];
}