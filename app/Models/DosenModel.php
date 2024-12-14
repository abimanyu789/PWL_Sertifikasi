<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenModel extends Model
{
    use HasFactory;
    protected $table = 't_dosen';
    protected $primaryKey = 'dosen_id';
    protected $fillable = [
        'user_id', 
        'bidang_id', 
        'mk_id'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
    
    public function mata_kuliah()
    {
        return $this->belongsTo(MatkulModel::class, 'mk_id', 'mk_id');
    }

    public function bidang()
    {
        return $this->belongsTo(BidangModel::class, 'bidang_id', 'bidang_id');
    }

    public function peserta_pelatihan()
    {
        return $this->hasMany(PesertaPelatihanModel::class, 'dosen_id', 'dosen_id');
    }

    public function peserta_sertifikasi()
    {
        return $this->hasMany(PesertaSertifikasiModel::class, 'dosen_id', 'dosen_id');
    }
}
