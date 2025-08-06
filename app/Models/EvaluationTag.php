<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'tag_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'evaluation_id' => 'integer',
        'tag_id' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
