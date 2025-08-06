<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Activity extends Model
{
    use HasFactory;

    protected  $fillable = [
        'title',
        'description',
        'images',
        'status'
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'images' => 'array',
        'status' => 'string',
    ];

    public function content(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Content::class, 'contentable');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'activity_tags', 'activity_id', 'tag_id')->withTimestamps();
    }

    public function activity_tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ActivityTag::class);
    }


    public function getImagesAttribute($values): array
    {
        $decoded = json_decode($values, true);

        // Return empty array if decode fails
        if (!is_array($decoded)) {
            return [];
        }

        // If it's an API request, return full URLs
        if (request()->is('api/*')) {
            return array_map(function ($path) {
                return url($path);
            }, $decoded);
        }

        // Otherwise, return just the relative paths
        return $decoded;
    }

}
