<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'm_user';        // mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id';  // mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = [
        'level_id',
        'nip', 
        'nama',
        'username',
        'email', 
        'password',
        'avatar',
        'created_at',
        'updated_at'
    ];

    protected $hidden   = ['password']; // jangan di tampilkan saat select

    protected $casts    = ['password' => 'hashed']; // casting password agar otomatis di hash

    /**
     * Relasi ke tabel level
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function upload_sertifikat() 
    {
        return $this->hasMany(UploadSertifModel::class, 'user_id', 'user_id');
    }

    //nama role
    public function getRoleName(): string{
        return $this->level->level_nama;
    }

    // apakah user memiliki role tertentu
    public function hasRole($role): bool{
        return $this->level->level_kode == $role;
    }

    // Mendapatkan kode role
    public function getRole()
    {
        return $this->level->level_kode;
    }

    // Implementasi metode dari JWTSubject
    public function getJWTIdentifier()
    {
        // Mengembalikan identifier unik pengguna (biasanya ID pengguna)
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // Mengembalikan klaim tambahan untuk token JWT (bisa dikosongkan jika tidak diperlukan)
        return [];
    }

}
