@extends('frontend.layouts.app')

@section('title', $category->name)
@section('meta_description', $category->description ? strip_tags($category->description) : 'Products in ' . $category->name)

@section('content')
<section class="relative overflow-hidden border-b border-slate-200 bg-neutral-950 text-white">
    <div class="absolute inset-0">
        @if ($category->image)
            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover opacity-35">
        @else
            <img src="{{ asset('uploads/ui/category-default.svg') }}" alt="{{ $category->name }}" class="h-full w-full object-cover opacity-35">
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-neutral-950 via-neutral-950/85 to-neutral-950/30"></div>
    </div>

    <div class="relative mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-[240px_1fr] lg:px-8">
        <div>
            @if ($category->image)
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-44 w-full rounded-lg border border-white/20 object-cover shadow-xl">
            @else
                <img src="{{ asset('uploads/ui/category-default.svg') }}" alt="{{ $category->name }}" class="h-44 w-full rounded-lg border border-white/20 object-cover shadow-xl">
            @endif
        </div>

        <div>
            <p class="text-xs font-black uppercase tracking-[0.25em] text-red-300">Category Catalogue</p>
            <h1 class="mt-3 text-4xl font-black md:text-5xl">{{ $category->name }}</h1>
            @if ($category->description)
                <div class="mt-4 max-w-3xl text-sm leading-7 text-white/80">{!! $category->description !!}</div>
            @endif

            @if ($category->subCategories->isNotEmpty())
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach ($category->subCategories as $subCategory)
                        <a href="{{ route('products.index', ['category' => $category->slug, 'sub_category' => $subCategory->slug]) }}" class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-bold text-white hover:bg-white hover:text-neutral-950">{{ $subCategory->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6 rounded-lg border border-red-100 bg-red-50 p-5 sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-black text-neutral-950">Looking for bulk quantity?</h2>
            <p class="mt-1 text-sm text-neutral-600">Share your requirement and get a focused catalogue quote.</p>
        </div>
        <a href="{{ route('bulk-quote.create') }}" class="mt-4 inline-flex rounded bg-neutral-950 px-5 py-3 text-xs font-black uppercase tracking-wide text-white hover:bg-neutral-800 sm:mt-0">Request a Quote</a>
    </div>

    <div class="mb-5 flex items-end justify-between">
        <div>
            <h2 class="text-2xl font-semibold">Products</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $products->total() }} active products found.</p>
        </div>
        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-sm font-medium text-slate-700 hover:text-slate-950">Filter view</a>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
        @forelse ($products as $product)
            @include('frontend.partials.product-card', ['product' => $product])
        @empty
            <div class="rounded border border-slate-200 bg-white p-6 text-sm text-slate-500 lg:col-span-4">No active products in this category.</div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</section>
@endsection
