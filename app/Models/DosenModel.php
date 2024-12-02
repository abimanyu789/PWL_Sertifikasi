<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenModel extends Model
{
    public function bidang()
    {
        return $this->belongsTo(BidangModel::class, 'bidang_id', 'bidang_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MatkulModel::class, 'matkul_id', 'matkul_id');
    }

    public function pelatihan()
    {
        return $this->belongsToMany(PelatihanModel::class, 'pelatihan_id', 'pelatihan_id');
    }
}