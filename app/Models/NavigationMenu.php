<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

class NavigationMenu extends Model
{
    public const TYPES = ['route', 'page', 'category', 'custom_url'];

    public const LOCATIONS = ['header', 'footer'];

    public const ALLOWED_ROUTES = [
        'home' => 'Home',
        'products.index' => 'Catalog',
        'bulk-quote.create' => 'Bulk Quote',
        'contact' => 'Contact',
    ];

    public const SEO_PAGE_ROUTES = [
        'about-us' => 'pages.about',
        'shipping-policy' => 'pages.shipping-policy',
        'returns-policy' => 'pages.returns-policy',
        'privacy-policy' => 'pages.privacy-policy',
        'terms-conditions' => 'pages.terms-conditions',
    ];

    protected $fillable = [
        'title',
        'type',
        'label',
        'url',
        'route_name',
        'page_id',
        'category_id',
        'parent_id',
        'location',
        'sort_order',
        'open_in_new_tab',
        'is_mega',
        'status',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'is_mega' => 'boolean',
        'status' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('label');
    }

    public function activeChildren(): HasMany
    {
        return $this->children()->where('status', true);
    }

    public function getResolvedUrlAttribute(): string
    {
        return match ($this->type) {
            'page' => $this->page?->slug ? $this->resolvedPageUrl() : ($this->url ?: '#'),
            'category' => $this->category?->slug ? route('products.category', $this->category->slug) : ($this->url ?: route('products.index')),
            'custom_url' => $this->url ?: '#',
            default => $this->route_name && Route::has($this->route_name) ? route($this->route_name) : ($this->url ?: '#'),
        };
    }

    private function resolvedPageUrl(): string
    {
        $slug = (string) $this->page?->slug;
        $routeName = self::SEO_PAGE_ROUTES[$slug] ?? null;

        if ($routeName && Route::has($routeName)) {
            return route($routeName);
        }

        return route('pages.show', $slug);
    }

    public function getIsExternalAttribute(): bool
    {
        return str_starts_with((string) $this->resolved_url, 'http://')
            || str_starts_with((string) $this->resolved_url, 'https://');
    }
}
