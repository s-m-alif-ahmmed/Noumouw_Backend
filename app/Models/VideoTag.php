<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'tag_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'video_id' => 'integer',
        'tag_id' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
