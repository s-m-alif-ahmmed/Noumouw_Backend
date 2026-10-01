<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseTokens extends Model
{
    use HasFactory;

    protected $table = 'firebase_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'device_id',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'token' => 'string',
        'device_id' => 'string',
        'is_active' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
