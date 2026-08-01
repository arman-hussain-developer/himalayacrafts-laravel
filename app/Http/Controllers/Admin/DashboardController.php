<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BrandLogo;
use App\Models\BulkQuoteRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductEnquiry;
use App\Models\SubCategory;
use App\Models\Testimonial;
use App\Models\TrustItem;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'categories' => Category::count(),
            'subCategories' => SubCategory::count(),
            'products' => Product::count(),
            'banners' => Banner::count(),
            'pages' => Page::count(),
            'testimonials' => Testimonial::count(),
            'trustItems' => TrustItem::count(),
            'brandLogos' => BrandLogo::count(),
            'orders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'completedOrders' => Order::whereIn('status', ['delivered'])->count(),
            'totalRevenue' => Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
                ->where('payment_status', 'paid')
                ->sum('total'),
            'newEnquiries' => ProductEnquiry::where('status', 'new')->count(),
            'totalEnquiries' => ProductEnquiry::count(),
            'newBulkQuotes' => BulkQuoteRequest::where('status', 'new')->count(),
            'totalBulkQuotes' => BulkQuoteRequest::count(),
        ];

        return view('admin.dashboard', compact('counts'));
    }
}
