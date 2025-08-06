<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
        'user_id'
    ];

    protected $casts = [
        'name' => 'string',
        'birth_date' => 'datetime',
        'user_id' => 'integer',
    ];

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
       return $this->belongsTo(User::class, 'user_id');
    }

}
