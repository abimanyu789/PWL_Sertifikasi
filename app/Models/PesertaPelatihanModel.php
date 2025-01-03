<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PesertaPelatihanModel extends Model
{
    use HasFactory;
    protected $table = 'peserta_pelatihan';
    protected $primaryKey = 'peserta_pelatihan_id';
    protected $fillable = [
        'user_id', 
        'pelatihan_id', 
        'status'
    ];

   
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(PelatihanModel::class, 'pelatihan_id', 'pelatihan_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            Log::info('Creating Peserta:', $model->toArray());
            return $model;
        });
    }

}
