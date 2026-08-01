<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'message',
        'rating',
        'image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];
}
