<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'price',
        'revenue_cart_product_id'
    ];

    protected $casts = [
        'name' => 'string',
        'duration' => 'string',
        'price' => 'string',
        'revenue_cart_product_id' => 'string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
