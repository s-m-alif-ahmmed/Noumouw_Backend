<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'answer',
        'link',
        'evaluation_id'
    ];

    protected $casts = [
        'title' => 'string',
        'answer' => 'integer',
        'link' => 'string',
        'evaluation_id' => 'integer',
    ];

    protected $hidden = [
    ];

    public function evaluation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }
}
