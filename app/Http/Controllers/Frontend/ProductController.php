<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Support\FrontendData;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = FrontendData::categories();
        $selectedCategory = null;
        $selectedSubCategory = null;

        $products = Product::with(['category', 'subCategory', 'specifications', 'colors'])
            ->where('status', true);

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();

            $products->where(function ($query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $selectedCategory = Category::where('status', true)
                ->where('slug', $request->string('category')->toString())
                ->first();

            if ($selectedCategory) {
                $products->where('category_id', $selectedCategory->id);
            } else {
                $products->whereRaw('1 = 0');
            }
        }

        if ($request->filled('sub_category')) {
            $selectedSubCategory = SubCategory::where('status', true)
                ->where('slug', $request->string('sub_category')->toString())
                ->first();

            if ($selectedSubCategory) {
                $products->where('sub_category_id', $selectedSubCategory->id);
                $selectedCategory = $selectedSubCategory->category;
            } else {
                $products->whereRaw('1 = 0');
            }
        }

        if ($request->filled('min_price')) {
            $products->whereRaw('COALESCE(sale_price, price) >= ?', [(float) $request->input('min_price')]);
        }

        if ($request->filled('max_price')) {
            $products->whereRaw('COALESCE(sale_price, price) <= ?', [(float) $request->input('max_price')]);
        }

        match ($request->string('sort')->toString()) {
            'price_low_high', 'price_low' => $products->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_high_low', 'price_high' => $products->orderByRaw('COALESCE(sale_price, price) desc'),
            'featured' => $products->orderByDesc('is_featured')->latest(),
            default => $products->latest(),
        };

        $products = $products->paginate(12)->withQueryString();

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'selectedCategory',
            'selectedSubCategory'
        ));
    }

    public function category(string $slug)
    {
        $category = Category::with([
            'subCategories' => fn ($query) => $query
                ->where('status', true)
                ->orderBy('name'),
        ])
            ->where('status', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $products = Product::with(['category', 'subCategory', 'specifications', 'colors'])
            ->where('status', true)
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        return view('frontend.products.category', compact('category', 'products'));
    }

    public function subcategory(string $slug)
    {
        $subCategory = SubCategory::with('category')
            ->where('status', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return redirect()->route('products.index', [
            'category' => $subCategory->category?->slug,
            'sub_category' => $subCategory->slug,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'subCategory', 'images', 'variants', 'specifications', 'colors'])
            ->where('status', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'subCategory', 'specifications', 'colors'])
            ->where('status', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
