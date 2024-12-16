<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratModel extends Model
{
    use HasFactory;
    protected $table = 'surat_tugas';
    protected $primaryKey = 'surat_tugas_id';
    protected $fillable = [
        'peserta_sertifikasi_id', 
        'peserta_pelatihan_id', 
        'user_id',
        'file_surat'
    ];

    public function peserta_pelatihan()
    {
        return $this->belongsTo(PesertaPelatihanModel::class, 'peserta_pelatihan_id', 'peserta_pelatihan_id');
    }

    public function peserta_sertifikasi()
    {
        return $this->belongsTo(PesertaSertifikasiModel::class, 'peserta_sertifikasi_id', 'peserta_sertifikasi_id');
    }

    public function user()
    {
        return $this->belongsTo(PesertaSertifikasiModel::class, 'user_id', 'user_id');
    }


    
}
