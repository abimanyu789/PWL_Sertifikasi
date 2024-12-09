<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    use HasFactory;
    protected $table = 'm_periode'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'periode_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['periode_tahun','created_at', 'updated_at'];
}