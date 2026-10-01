<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_id',
        'course_id',
        'type',
        'is_completed'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'content_id' => 'integer',
        'course_id' => 'integer',
        'type' => 'string',
        'is_completed' => 'string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
