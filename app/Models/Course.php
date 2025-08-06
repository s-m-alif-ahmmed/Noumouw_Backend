<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'status',
        'subscription_plans_id'
    ];

    protected $hidden = [
        'subscription_plans_id',
        'description'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'thumbnail' => 'string',
        'status' => 'string',
        'subscription_plans_id' => 'integer',
    ];

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'course_tags', 'course_id', 'tag_id')->withTimestamps();
    }

    public function course_tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseTag::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plans_id');
    }

    public function contents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Content::class);
    }

    public static function activeCourse()
    {
        return self::query()->where('status', 'active');
    }

    public function getThumbnailAttribute($value): string | null
    {
        // Check if the request is an API request
        if (request()->is('api/*') && !empty($value)) {
            // Return the full URL for API requests
            return url($value);
        }
        // Return only the path for web requests
        return $value;
    }

}
