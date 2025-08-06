<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationAnswer extends Model
{
    use HasFactory;

    protected  $fillable = [
        'user_id',
        'course_id',
        'evaluation_id',
        'question_id',
        'answer',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'course_id' => 'integer',
        'evaluation_id' => 'integer',
        'question_id' => 'integer',
        'answer' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function evaluation(){
        return $this->belongsTo(Evaluation::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

}
