<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratModel extends Model
{
    use HasFactory;
    protected $table = 'surat_tugas ';
    protected $primaryKey = 'surat_tugas_id';
    protected $fillable = [
        'peserta_sertifikasi_id', 
        'peserta_pelatihan_id', 
        'surat_tugas_nama',
        'file_surat_tugas'
    ];

    public function peserta_pelatihan()
    {
        return $this->belongsTo(PesertaModel::class, 'user_id', 'user_id');
    }

    public function peserta_sertifikasi()
    {
        return $this->belongsTo(PesertaSertifikasiModel::class, 'user_id', 'user_id');
    }

    
}
