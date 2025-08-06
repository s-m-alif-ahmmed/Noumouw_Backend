<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetStart extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
    ];

    protected $casts = [
        'description' => 'string',
    ];

}
