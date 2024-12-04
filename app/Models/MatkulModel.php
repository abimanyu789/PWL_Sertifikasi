<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatkulModel extends Model
{
    use HasFactory;
    protected $table = 'm_mata_kuliah'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'mk_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['mk_kode','mk_nama','created_at', 'updated_at'];
}
