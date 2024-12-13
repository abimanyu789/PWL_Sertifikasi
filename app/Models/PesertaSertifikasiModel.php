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
        'user_id', 
        'sertifikasi_id', 
        'status'
    ];

   
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function sertifikasi()
    {
        return $this->belongsTo(SertifikasiModel::class, 'pelatihan_id', 'pelatihan_id');
    }
}
