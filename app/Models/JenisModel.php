<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisModel extends Model
{
    use HasFactory;
    protected $table = 'm_jenis'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'jenis_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['jenis_nama','jenis_kode','created_at', 'updated_at'];
}
