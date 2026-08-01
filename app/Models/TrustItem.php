<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrustItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];
}
