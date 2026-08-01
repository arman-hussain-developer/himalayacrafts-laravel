<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;

class PageController extends Controller
{
    public function showBySlug(string $slug)
    {
        return $this->show($slug);
    }

    public function show(string $slug)
    {
        $page = Page::where('status', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $categories = $slug === 'about-us'
            ? Category::where('status', true)->orderBy('name')->get()
            : collect();

        return view('frontend.pages.show', compact('page', 'categories'));
    }
}
