<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'podcast_id',
        'tag_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'podcast_id' => 'integer',
        'tag_id' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }
}
