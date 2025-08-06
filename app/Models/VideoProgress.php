<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'progress_percentage'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'video_id' => 'integer',
        'progress_percentage' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
