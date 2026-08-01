<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductEnquiry extends Model
{
    protected $fillable = [
        'product_id',
        'variant_id',
        'product_name',
        'customer_name',
        'customer_phone',
        'customer_email',
        'message',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
