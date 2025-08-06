<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'parent_role',
        'is_parent',
        'country'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'birth_date' => 'datetime',
        'parent_role' => 'string',
        'is_parent' => 'integer',
        'country' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
