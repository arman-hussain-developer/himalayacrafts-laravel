<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandLogo extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'link',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];
}
