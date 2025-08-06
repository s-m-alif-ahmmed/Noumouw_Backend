<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'tag_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'course_id' => 'integer',
        'tag_id' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
