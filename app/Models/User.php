<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'email_verified_at',
        'role',
        'remember_token',
        'status',
        'reset_password_token',
        'reset_password_token_exp',
        'accept_push_notifications',
        'email_newsletter',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'email_verified_at',
        'deleted_at',
        'created_at',
        'updated_at',
        'role',
        'status',
        'remember_token',
        'reset_password_token',
        'reset_password_token_exp'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
            'avatar' => 'string',
            'role' => 'string',
            'email_verified_at' => 'datetime',
            'remember_token' => 'string',
            'status' => 'string',
            'reset_password_token' => 'string',
            'reset_password_token_exp' => 'datetime',
            'device_token' => 'string',
            'password' => 'hashed',
            'accept_push_notifications' => 'boolean',
            'email_newsletter' => 'boolean'
        ];
    }

    protected $appends = [
        // 'subscribed_course_rating',
    ];

    public function getAvatarAttribute($value): string | null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        // Check if the request is an API request
        if (request()->is('api/*') && !empty($value)) {
            // Return the full URL for API requests
            return url($value);
        }

        // Return only the path for web requests
        return $value;
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Children::class);
    }

    public function firebaseTokens(): HasMany
    {
        return $this->hasMany(FirebaseTokens::class,'user_id');
    }

    public function user_tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'user_tags', 'user_id', 'tag_id')->withTimestamps();
    }

    public function subscribed_courses(){
        return $this->hasMany(UserSubscription::class);
    }

    // public function getSubscribedCourseRatingAttribute(){
    //     $courseIds = $this->subscribed_courses()->pluck('course_id');
    //     return CourseRating::whereIn('course_id', $courseIds)->with('user:id,name,avatar')->get();
    // }

    

}
