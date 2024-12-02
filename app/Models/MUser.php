<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class MUser extends Model
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'level_id',
        'nip',
        'nama',
        'email',
        'password',
        'avatar',
    ];

    // Fungsi untuk mengenkripsi password secara otomatis
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
