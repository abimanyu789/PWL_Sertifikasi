<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadSertifModel extends Model
{
    use HasFactory;
    protected $table = 'upload_sertifikat';        // mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'sertif_id 	';  // mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = [
        'user_id',
        'jenis_kegiatan',
        'no_sertif',
        'nama_sertif',
        'tanggal_pelaksanaan',
        'tanggal_berlaku',
        'bidang_id',
        'vendor_id',
        'image',
        'created_at',
        'updated_at'
    ];
}
