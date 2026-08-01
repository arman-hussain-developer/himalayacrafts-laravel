<?php

namespace App\Support;

use App\Models\Category;
use App\Models\NavigationMenu;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class FrontendData
{
    private static ?Collection $categories = null;

    private static ?Collection $settings = null;

    private static ?Collection $headerMenus = null;

    private static ?Collection $footerMenus = null;

    public static function categories(): Collection
    {
        if (self::$categories === null) {
            self::$categories = Category::with([
                'subCategories' => fn ($query) => $query
                    ->where('status', true)
                    ->orderBy('name'),
            ])
                ->where('status', true)
                ->orderBy('name')
                ->get();
        }

        return self::$categories;
    }

    public static function settings(): Collection
    {
        if (self::$settings === null) {
            self::$settings = Setting::pluck('value', 'setting_key');
        }

        return self::$settings;
    }

    public static function setting(string $key, mixed $default = null): mixed
    {
        return self::settings()->get($key, $default);
    }

    public static function headerMenus(): Collection
    {
        return self::menus('header', self::$headerMenus);
    }

    public static function footerMenus(): Collection
    {
        return self::menus('footer', self::$footerMenus);
    }

    private static function menus(string $location, ?Collection &$cache): Collection
    {
        if ($cache === null) {
            if (! Schema::hasTable('navigation_menus')) {
                $cache = collect();
            } else {
                $cache = NavigationMenu::with([
                    'page',
                    'category',
                    'activeChildren.page',
                    'activeChildren.category',
                ])
                    ->where('status', true)
                    ->where('location', $location)
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->orderBy('label')
                    ->get();
            }
        }

        return $cache;
    }
}
