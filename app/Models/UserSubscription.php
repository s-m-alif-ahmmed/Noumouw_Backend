<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'start_date',
        'end_date',
        'subscription_name',
        'subscription_price',
        'subscription_duration',
    ];

    

    protected $appends = [
        'is_expired',
        'expiry_date',
        'course_info',
        'completed_contents',
        'total_contents',
        'progress_percentage',
    ];

    public function getIsExpiredAttribute()
    {
        return now()->gt($this->end_date);
    }

    public function getExpiryDateAttribute()
    {
        return $this->end_date;
    }

    public function getCourseInfoAttribute(){
        return $this->course;
    }

    public function getCompletedContentsAttribute()
    {
        return \App\Models\ContentCompletion::where([
            ['user_id', '=', $this->user_id],
            ['course_id', '=', $this->course_id],
            ['is_completed', '=', 'Yes']
        ])->count();
    }

    public function getTotalContentsAttribute()
    {
        if (!$this->course) {
            return 0;
        }
        return $this->course->contents()->count();
    }

    public function getProgressPercentageAttribute()
    {
        $total = $this->total_contents;
        if ($total === 0) {
            return 0;
        }
        return round(($this->completed_contents / $total) * 100, 2);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    protected $hidden = [
        "updated_at",
        "created_at"
    ];
}
