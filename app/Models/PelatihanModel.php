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
        'kuota',
        'lokasi',
        'biaya',
        'level_pelatihan',
        'vendor_id',
        'jenis_id',
        'mk_id',
        'periode_id'
    ];

    public function vendor()
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisModel::class, 'jenis_id', 'jenis_id');
    }

    public function mata_kuliah()
    {
        return $this->belongsTo(MatkulModel::class, 'mk_id', 'mk_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeModel::class, 'periode_id', 'periode_id');
    }

    // PelatihanModel.php
    public function peserta_pelatihan()
    {
        return $this->hasMany(PesertaPelatihanModel::class, 'pelatihan_id', 'pelatihan_id');
    }
}