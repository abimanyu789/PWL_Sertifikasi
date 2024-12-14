<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikasiModel extends Model
{
    protected $table = 'm_sertifikasi';
    protected $primaryKey = 'sertifikasi_id';
    protected $fillable = [
        'nama_sertifikasi',
        'deskripsi',
        'tanggal',
        'kuota',
        'lokasi',
        'biaya',
        'level_sertifikasi',
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

    public function peserta_sertifikasi()
    {
        return $this->hasMany(PesertaSertifikasiModel::class, 'dosen_id', 'dosen_id');
    }
}