<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BrandLogo;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\TrustItem;
use App\Support\FrontendData;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', true)
            ->latest()
            ->get();

        $featuredProducts = Product::with(['category', 'subCategory', 'specifications', 'colors'])
            ->where('status', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::with(['category', 'subCategory', 'specifications', 'colors'])
            ->where('status', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = FrontendData::categories();
        $testimonials = Testimonial::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->get();
        $trustItems = TrustItem::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->get();
        $brandLogos = BrandLogo::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->get();
        $homeSectionSettings = FrontendData::settings();

        return view('frontend.home', compact(
            'banners',
            'featuredProducts',
            'latestProducts',
            'categories',
            'testimonials',
            'trustItems',
            'brandLogos',
            'homeSectionSettings'
        ));
    }
}
