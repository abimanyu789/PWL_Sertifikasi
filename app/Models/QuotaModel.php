<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    use HasFactory;
    protected $table = 'm_quota';
    protected $primaryKey = 'quota_id';
    protected $fillable = [
        'pelatihan_id',
        'quota_jumlah',   
    ];

    public function pelatihan()
    {
        return $this->belongsTo(PelatihanModel::class, 'pelatihan_id', 'pelatihan_id');
    }
}