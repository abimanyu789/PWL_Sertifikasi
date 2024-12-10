<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangModel extends Model
{
    use HasFactory;
    protected $table = 'm_bidang'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'bidang_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = [
        'bidang_kode',
        'bidang_nama',
        'jenis_id'  // Tanpa spasi
    ];

    public function jenis()
    {
        return $this->belongsTo(JenisModel::class, 'jenis_id', 'jenis_id');
    }

}
