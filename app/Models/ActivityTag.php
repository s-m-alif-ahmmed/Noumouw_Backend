<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'tag_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'activity_id' => 'integer',
        'tag_id' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
