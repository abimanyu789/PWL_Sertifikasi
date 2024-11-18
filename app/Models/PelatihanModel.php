<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelatihanModel extends Model
{
    protected $table = 'm_pelatihan';
    protected $primaryKey = 'pelatihan_id';
    protected $fillable = [
        'nama_pelatihan',
        'deskripsi',
        'tanggal',
        'bidang_id',
        'level_pelatihan_id',
        'vendor_id'
    ];

    public function bidang()
    {
        return $this->belongsTo(BidangModel::class, 'bidang_id', 'bidang_id');
    }

    public function level_pelatihan()
    {
        return $this->belongsTo(LevelPelatihanModel::class, 'level_pelatihan_id', 'level_pelatihan_id');
    }

    public function vendor()
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
    }

}