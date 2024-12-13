<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiModel extends Model
{
    use HasFactory;
    protected $table = 'notification';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'reference_id',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}
