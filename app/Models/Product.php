<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'branding_details',
        'packaging_details',
        'production_timeline',
        'moq',
        'printing_methods',
        'price',
        'sale_price',
        'stock',
        'featured_image',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)->where('image_type', 'gallery')->orderBy('sort_order')->orderBy('id');
    }

    public function brandingMockupImages()
    {
        return $this->hasMany(ProductImage::class)->where('image_type', 'branding_mockup')->orderBy('sort_order')->orderBy('id');
    }

    public function packagingImages()
    {
        return $this->hasMany(ProductImage::class)->where('image_type', 'packaging')->orderBy('sort_order')->orderBy('id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class)->orderBy('sort_order');
    }

    public function enquiries()
    {
        return $this->hasMany(ProductEnquiry::class);
    }
}
