<?php

namespace App\Providers;

use App\Support\FrontendData;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('frontend.layouts.app', function ($view): void {
            $cartCount = collect(session('cart', []))->sum('quantity');

            $view->with([
                'frontendCategories' => FrontendData::categories(),
                'frontendHeaderMenus' => FrontendData::headerMenus(),
                'frontendFooterMenus' => FrontendData::footerMenus(),
                'frontendSettings' => FrontendData::settings(),
                'cartCount' => $cartCount,
            ]);
        });
    }
}
