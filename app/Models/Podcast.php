<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file',
        'instructor_id',
        'status'
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'file' => 'string',
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

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'podcast_tags', 'podcast_id', 'tag_id')->withTimestamps();
    }

    public function podcast_tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Podcast::class);
    }

}
