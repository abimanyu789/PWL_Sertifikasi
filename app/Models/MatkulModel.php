<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatkulModel extends Model
{
    use HasFactory;

    protected $table = 'm_matkul'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'matkul_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['matkul_nama','created_at', 'updated_at'];
}
