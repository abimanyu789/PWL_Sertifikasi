<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PesertaSertifikasiModel extends Model
{
    use HasFactory;
    protected $table = 'peserta_sertifikasi';
    protected $primaryKey = 'peserta_sertifikasi_id';
    protected $fillable = [
        'user_id', 
        'sertifikasi_id', 
        'status'
    ];

   
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function sertifikasi()
    {
        return $this->belongsTo(SertifikasiModel::class, 'sertifikasi_id', 'sertifikasi_id');
    }

    public function upload_sertifikasi()
    {
        return $this->hasOne(UploadSertifikasiModel::class, 'peserta_id', 'peserta_sertifikasi_id');
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
