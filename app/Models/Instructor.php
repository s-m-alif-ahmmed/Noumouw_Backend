<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected  $fillable = [
        'name',
        'avatar',
        'bio',
        'phone',
        'email',
        'address',
        'role',
        'designation',
        'country',
        'services'
    ];

    protected $casts = [
        'name' => 'string',
        'avatar' => 'string',
        'bio' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'address' => 'string',
        'role' => 'string',
        'designation' => 'string',
        'country' => 'string',
        'services' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

}
