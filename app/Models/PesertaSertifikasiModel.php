<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaSertifikasiModel extends Model
{
    use HasFactory;
    protected $table = 'peserta_sertifikasi';
    protected $primaryKey = 'peserta_sertifikasi_id';
    protected $fillable = [
        'dosen_id', 
        'sertifikasi_id', 
        'status'
    ];

   
    public function dosen()
    {
        return $this->hasOne(DosenModel::class, 'dosen_id', 'dosen_id');
    }

    public function sertifikasi()
    {
        return $this->belongsTo(SertifikasiModel::class, 'pelatihan_id', 'pelatihan_id');
    }
}
