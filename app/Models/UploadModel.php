<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadModel extends Model
{
    protected $table = 'upload_sertifikat';
    protected $primaryKey = 'sertif_id';
    protected $fillable = [
        'user_id',
        'no_sertif',
        'nama_sertif',
        'tanggal_pelaksanaan',
        'tanggal_berlaku',
        'bidang_id',
        'vendor_id',
        'image',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    // Relasi ke Bidang
    public function bidang()
    {
        return $this->belongsTo(BidangModel::class, 'bidang_id', 'bidang_id');
    }
    public function vendor()
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
    }


}