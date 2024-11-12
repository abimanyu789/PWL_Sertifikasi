<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelPelatihanModel extends Model
{
    use HasFactory;
    protected $table = 'm_level_pelatihan'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'level_pelatihan_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['level_pelatihan_nama','level_pelatihan_kode','created_at', 'updated_at'];
}
