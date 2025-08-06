<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicPage extends Model
{
    use HasFactory;

    protected $fillable = [
        "page_title",
        "page_content",
        "page_slug",
        "status"
    ];

    protected $casts = [
        'page_title' => 'string',
        'page_content' => 'string',
        'page_slug' => 'string',
        'status' => 'string',
    ];
}
