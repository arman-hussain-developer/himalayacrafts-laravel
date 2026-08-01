<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkQuoteRequest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'company_name',
        'category_id',
        'requirement',
        'quantity',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
