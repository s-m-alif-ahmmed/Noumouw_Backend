<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file',
        'duration',
        'instructor_id',
        'status'
    ];

    protected $casts = [
        'title' => 'string',
        'file' => 'string',
        'duration' => 'string',
        'instructor_id' => 'integer',
        'status' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function content(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Content::class, 'contentable');
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function privateVideo(): string
    {
        return route('private.video',['path'=>$this->file,'v'=>time()]);
    }

    public function privateVideoApi(): string
    {
        return route('private.video.api',['path'=>$this->file,'v'=>time()]);
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'video_tags', 'video_id', 'tag_id')->withTimestamps();
    }

    public function video_tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VideoTag::class);
    }

}
