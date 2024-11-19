<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikasiModel extends Model
{
    protected $table = 'm_sertifikasi';
    protected $primaryKey = 'sertifikasi_id';
    protected $fillable = [
        'nama_sertifikasi',
        'tanggal',
        'tanggal_berlaku',
        'bidang_id',
        'jenis_id'
    ];
}