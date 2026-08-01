<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandLogoController;
use App\Http\Controllers\Admin\BulkQuoteController as AdminBulkQuoteController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductEnquiryController as AdminProductEnquiryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TrustItemController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\BulkQuoteController as FrontendBulkQuoteController;
use App\Http\Controllers\Frontend\CartController as FrontendCartController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\ProductEnquiryController as FrontendProductEnquiryController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', [FrontendHomeController::class, 'index'])->name('home');
Route::get('/products', [FrontendProductController::class, 'index'])->name('products.index');
Route::get('/category/{slug}', [FrontendProductController::class, 'category'])->name('products.category');
Route::get('/subcategory/{slug}', [FrontendProductController::class, 'subcategory'])->name('products.subcategory');
Route::get('/product/{slug}', [FrontendProductController::class, 'show'])->name('products.show');
Route::get('/about', [FrontendPageController::class, 'showBySlug'])->defaults('slug', 'about-us')->name('pages.about');
Route::get('/shipping-policy', [FrontendPageController::class, 'showBySlug'])->defaults('slug', 'shipping-policy')->name('pages.shipping-policy');
Route::get('/returns-policy', [FrontendPageController::class, 'showBySlug'])->defaults('slug', 'returns-policy')->name('pages.returns-policy');
Route::get('/privacy-policy', [FrontendPageController::class, 'showBySlug'])->defaults('slug', 'privacy-policy')->name('pages.privacy-policy');
Route::get('/terms-conditions', [FrontendPageController::class, 'showBySlug'])->defaults('slug', 'terms-conditions')->name('pages.terms-conditions');
Route::get('/page/{slug}', [FrontendPageController::class, 'show'])->name('pages.show');
Route::get('/contact', [FrontendContactController::class, 'index'])->name('contact');
Route::get('/bulk-quote', [FrontendBulkQuoteController::class, 'create'])->name('bulk-quote.create');
Route::post('/bulk-quote', [FrontendBulkQuoteController::class, 'store'])->name('bulk-quote.store');
Route::get('/cart', [FrontendCartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [FrontendCartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [FrontendCartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{itemKey}', [FrontendCartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/checkout', [FrontendCartController::class, 'checkout'])->name('cart.checkout');
Route::post('/product-enquiry', [FrontendProductEnquiryController::class, 'store'])->name('product-enquiry.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('sub-categories', SubCategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('banners', BannerController::class)->except(['show']);
        Route::resource('pages', PageController::class)->except(['show']);
        Route::resource('menus', MenuController::class)->except(['show']);
        Route::resource('testimonials', TestimonialController::class)->except(['show']);
        Route::resource('trust-items', TrustItemController::class)->except(['show']);
        Route::resource('brand-logos', BrandLogoController::class)->except(['show']);
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
        Route::get('enquiries/export', [AdminProductEnquiryController::class, 'export'])->name('enquiries.export');
        Route::resource('enquiries', AdminProductEnquiryController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get('bulk-quotes/export', [AdminBulkQuoteController::class, 'export'])->name('bulk-quotes.export');
        Route::resource('bulk-quotes', AdminBulkQuoteController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('home-sections', [HomeSectionController::class, 'edit'])->name('home-sections.edit');
        Route::post('home-sections', [HomeSectionController::class, 'update'])->name('home-sections.update');
    });

});
