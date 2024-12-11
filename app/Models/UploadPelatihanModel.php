<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'upload_pelatihan';
    protected $primaryKey = 'upload_id';
    protected $fillable = [
        'user_id',
        'nama_sertif',
        'no_sertif',
        'tanggal',
        'masa_berlaku',
        'jenis_id',
        'nama_vendor',
        'bukti',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
    
    public function jenis()
    {
        return $this->belongsTo(JenisModel::class, 'jenis_id', 'jenis_id');
    }

}
