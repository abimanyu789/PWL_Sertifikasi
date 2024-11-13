<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangModel extends Model
{
    use HasFactory;

    protected $table = 'm_bidang'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'bidang_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['bidang_nama','bidang_kode','created_at', 'updated_at'];
}
