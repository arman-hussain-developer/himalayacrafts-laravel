@extends('frontend.layouts.app')

@section('title', $product->name . ' | Corporate Catalogue')
@section('meta_description', $product->short_description ? strip_tags($product->short_description) : $product->name)

@section('content')
@php
    $displayPrice = $product->sale_price ?: $product->price;
    $hasPrice = (float) $displayPrice > 0;
    $whatsappUrl = \App\Support\WhatsApp::productUrl($product->name);
    $selectedVariantId = (string) old('variant_id', '');
    $detailsHtml = $product->description ?: $product->short_description;
    $mediaItems = collect();

    if ($product->featured_image) {
        $mediaItems->push([
            'src' => asset($product->featured_image),
            'alt' => $product->name,
            'type' => 'gallery',
            'label' => 'Featured',
        ]);
    }

    foreach ($product->images as $image) {
        $mediaType = $image->image_type ?: 'gallery';
        $mediaItems->push([
            'src' => asset($image->image),
            'alt' => $product->name,
            'type' => $mediaType,
            'label' => match ($mediaType) {
                'branding_mockup' => 'Branding',
                'packaging' => 'Packaging',
                default => 'Gallery',
            },
        ]);
    }

    if ($mediaItems->isEmpty()) {
        $mediaItems->push([
            'src' => asset('uploads/ui/category-default.svg'),
            'alt' => $product->name,
            'type' => 'gallery',
            'label' => 'Preview',
        ]);
    }

    $galleryImages   = $mediaItems->where('type', 'gallery')->values();
    $brandingImages  = $mediaItems->where('type', 'branding_mockup')->values();
    $packagingImages = $mediaItems->where('type', 'packaging')->values();
    $mainImage       = $galleryImages->first()['src'] ?? $mediaItems->first()['src'];
    $categoryLabel   = collect([$product->category?->name, $product->subCategory?->name])->filter()->implode(' / ');

    $hasBrandingSection  = $product->branding_details || $product->printing_methods || $brandingImages->isNotEmpty();
    $hasPackagingSection = $product->packaging_details || $product->moq || $product->production_timeline || $packagingImages->isNotEmpty();
    $hasBrandingTab      = $hasBrandingSection || $hasPackagingSection;
    $hasSpecsTab         = $product->specifications->isNotEmpty();
    $hasDescTab          = (bool) $detailsHtml;
    $firstTab            = $hasSpecsTab ? 'specifications' : ($hasBrandingTab ? 'branding' : '');
@endphp

{{-- Mobile sticky enquiry bar (hidden once enquiry form scrolls into view) --}}
<div id="mobile-sticky-cta" class="fixed bottom-0 left-0 right-0 z-40 border-t border-neutral-200 bg-white px-4 py-3 shadow-[0_-4px_12px_rgba(0,0,0,0.08)] lg:hidden">
    <a href="#product-enquiry" class="flex items-center justify-center gap-2 bg-neutral-950 px-5 py-3 text-sm font-bold text-white transition hover:bg-neutral-800">
        <i class="fa-regular fa-envelope"></i>
        Send Product Enquiry
    </a>
</div>

<section class="bg-[#f7f4ef] pb-20 lg:pb-0">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-10">

        {{-- Breadcrumb --}}
        <div class="mb-5 flex flex-wrap items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-neutral-500">
            <a href="{{ route('home') }}" class="hover:text-red-700">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-red-700">Catalog</a>
            @if ($product->category)
                <span>/</span>
                <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-red-700">{{ $product->category->name }}</a>
            @endif
            @if ($product->subCategory)
                <span>/</span>
                <a href="{{ route('products.index', ['category' => $product->category?->slug, 'sub_category' => $product->subCategory->slug]) }}" class="hover:text-red-700">{{ $product->subCategory->name }}</a>
            @endif
        </div>

        {{-- Main product grid --}}
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_420px] xl:grid-cols-[minmax(0,1fr)_460px]">

            {{-- ── LEFT: Image gallery ── --}}
            <div class="min-w-0">

                {{-- Main image --}}
                <div class="border border-neutral-200 bg-white">
                    <div class="group relative flex aspect-[4/3] items-center justify-center overflow-hidden bg-[#fbfaf8] p-5">
                        <img id="main-product-image"
                             src="{{ $mainImage }}"
                             alt="{{ $product->name }}"
                             class="h-full w-full object-contain transition duration-500 group-hover:scale-[1.035]">
                        {{-- Zoom hint --}}
                        <span class="absolute bottom-3 right-3 hidden rounded-full bg-white/80 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-neutral-500 backdrop-blur-sm sm:inline-flex">
                            <i class="fa-solid fa-magnifying-glass mr-1 text-[9px]"></i>Gallery
                        </span>
                    </div>
                </div>

                {{-- Thumbnail strip --}}
                @if ($mediaItems->count() > 1)
                    <div class="mt-3 flex gap-3 overflow-x-auto pb-1 scrollbar-hide sm:grid sm:grid-cols-6 sm:overflow-visible sm:pb-0">
                        @foreach ($mediaItems as $mediaItem)
                            <button type="button"
                                    data-gallery-thumbnail
                                    data-gallery-src="{{ $mediaItem['src'] }}"
                                    class="group shrink-0 border bg-white p-1 text-left transition hover:border-neutral-500 {{ $mediaItem['src'] === $mainImage ? 'border-red-600' : 'border-neutral-200' }}">
                                <span class="flex h-20 w-24 items-center justify-center overflow-hidden bg-[#fbfaf8] sm:h-24 sm:w-full">
                                    <img src="{{ $mediaItem['src'] }}" alt="{{ $mediaItem['alt'] }}" class="h-full w-full object-contain transition duration-300 group-hover:scale-105">
                                </span>
                                <!--<span class="mt-1 block truncate px-1 text-[10px] font-semibold uppercase tracking-wide text-neutral-500">{{ $mediaItem['label'] }}</span>-->
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Mobile-only: quick spec chips under thumbnails --}}
                @if ($product->specifications->isNotEmpty())
                    <div class="mt-4 flex flex-wrap gap-2 lg:hidden">
                        @foreach ($product->specifications->take(4) as $spec)
                            <span class="flex items-center gap-1.5 border border-neutral-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-neutral-700">
                                <i class="{{ $spec->icon ?: 'fa-solid fa-circle-check' }} text-red-600 text-[10px]"></i>
                                {{ $spec->label }}: {{ $spec->value }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Product description with read-more toggle --}}
                @if ($detailsHtml)
                    <div class="mt-5 border border-neutral-200 bg-white p-5">
                        <h2 class="text-sm font-bold uppercase tracking-wide text-neutral-950">Product Description</h2>
                        <div id="product-desc-wrap" class="relative mt-3 overflow-hidden" style="max-height:9rem">
                            <div class="text-[15px] leading-8 text-neutral-700
                                        [&_a]:font-semibold [&_a]:text-red-700
                                        [&_li]:my-1.5 [&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-5
                                        [&_p]:mb-4 [&_strong]:font-semibold [&_strong]:text-neutral-950
                                        [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-5">
                                {!! $detailsHtml !!}
                            </div>
                            <div id="product-desc-fade"
                                 class="pointer-events-none absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-white to-transparent"></div>
                        </div>
                        <button type="button" id="product-desc-toggle"
                                class="mt-3 text-sm font-semibold text-red-700 hover:underline">
                            Read more <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                    </div>
                @endif

            </div>{{-- / LEFT --}}

            {{-- ── RIGHT: Product info + enquiry (sticky on desktop) ── --}}
            <aside class="min-w-0 lg:sticky lg:top-28 lg:self-start">

                {{-- Product info card --}}
                <div class="border border-neutral-200 bg-white p-5 sm:p-6">

                    {{-- Category label --}}
                    @if ($categoryLabel)
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-red-700">{{ $categoryLabel }}</p>
                    @endif

                    {{-- Product name --}}
                    <h1 class="mt-2 text-2xl font-bold leading-tight text-neutral-950 sm:text-3xl">{{ $product->name }}</h1>

                    {{-- SKU badge --}}
                    @if ($product->sku)
                        <div class="mt-2.5">
                            <span class="inline-flex items-center gap-1.5 border border-neutral-200 bg-neutral-50 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-neutral-600">
                                <i class="fa-solid fa-barcode text-neutral-400 text-[10px]"></i>
                                {{ $product->sku }}
                            </span>
                        </div>
                    @endif

                    {{-- Short description --}}
                    @if ($product->short_description)
                        <div class="mt-4 text-[15px] leading-7 text-neutral-600">{!! $product->short_description !!}</div>
                    @endif

                    {{-- Price / B2B badge --}}
                    <div class="mt-5 flex flex-wrap items-center gap-3 border-y border-neutral-200 py-4">
                        @if ($hasPrice)
                            <span class="text-2xl font-bold text-neutral-950">Rs.&nbsp;{{ number_format((float) $displayPrice, 2) }}</span>
                            @if ($product->sale_price)
                                <span class="text-sm text-neutral-400 line-through">Rs.&nbsp;{{ number_format((float) $product->price, 2) }}</span>
                            @endif
                        @else
                            <span class="border border-red-200 bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700">Price on enquiry</span>
                        @endif
                        <span class="border border-neutral-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-neutral-600">B2B Catalogue</span>
                    </div>

                    {{-- Key info chips: Availability / MOQ / Timeline --}}
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="border border-neutral-200 p-3">
                            <div class="text-[10px] font-semibold uppercase tracking-wide text-neutral-500">Availability</div>
                            <div class="mt-1 text-sm font-semibold text-neutral-950">{{ $product->stock > 0 ? 'Available for enquiry' : 'Confirm availability' }}</div>
                        </div>
                        @if ($product->moq)
                            <div class="border border-neutral-200 p-3">
                                <div class="flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-neutral-500">
                                    <i class="fa-solid fa-boxes-stacked text-red-500 text-[9px]"></i> MOQ
                                </div>
                                <div class="mt-1 text-sm font-semibold text-neutral-950">{{ $product->moq }}</div>
                            </div>
                        @endif
                        @if ($product->production_timeline)
                            <div class="border border-neutral-200 p-3 {{ $product->moq ? '' : 'col-span-2' }}">
                                <div class="flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-neutral-500">
                                    <i class="fa-regular fa-clock text-red-500 text-[9px]"></i> Timeline
                                </div>
                                <div class="mt-1 text-sm font-semibold text-neutral-950">{{ $product->production_timeline }}</div>
                            </div>
                        @endif
                    </div>

                    {{-- Feature tags --}}
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs font-semibold text-neutral-700">
                            <i class="fa-solid fa-layer-group mr-1 text-neutral-400 text-[10px]"></i>Bulk orders
                        </span>
                        <span class="border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs font-semibold text-neutral-700">
                            <i class="fa-solid fa-stamp mr-1 text-neutral-400 text-[10px]"></i>Branding support
                        </span>
                        <span class="border border-neutral-200 bg-neutral-50 px-3 py-1.5 text-xs font-semibold text-neutral-700">
                            <i class="fa-solid fa-bolt mr-1 text-neutral-400 text-[10px]"></i>Fast quote
                        </span>
                    </div>

                    {{-- Color swatches — click switches main image --}}
                    @if ($product->colors->isNotEmpty())
                        <div class="mt-5 border-t border-neutral-100 pt-5">
                            <h2 class="text-xs font-bold uppercase tracking-wide text-neutral-950">Available Colors</h2>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($product->colors as $color)
                                    @php
                                        $swatchColor = $color->color_code && preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/', $color->color_code) ? $color->color_code : null;
                                        $swatchStyle = $color->color_image
                                            ? 'background-image:url(' . asset($color->color_image) . ');background-size:cover;background-position:center;'
                                            : ($swatchColor ? 'background-color:' . $swatchColor . ';' : 'background-color:#e5e7eb;');
                                    @endphp
                                    <button type="button"
                                            class="product-color-option group flex min-w-[4rem] flex-col items-center gap-1.5 border border-neutral-200 bg-white px-3 py-2 text-xs font-semibold text-neutral-700 transition hover:border-red-500 hover:bg-red-50"
                                            data-color-name="{{ $color->color_name }}"
                                            data-color-image="{{ $color->color_image ? asset($color->color_image) : '' }}">
                                        <span class="h-7 w-7 rounded-full border border-neutral-200 shadow-sm transition group-hover:scale-110"
                                              style="{{ $swatchStyle }}"></span>
                                        <span class="max-w-[5rem] truncate">{{ $color->color_name }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Key specifications (icon cards, first 6) --}}
                    @if ($product->specifications->isNotEmpty())
                        <div class="mt-5 border-t border-neutral-100 pt-5">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xs font-bold uppercase tracking-wide text-neutral-950">Key Specifications</h2>
                                @if ($firstTab)
                                    <button type="button" data-open-tab="specifications"
                                            class="text-xs font-semibold text-red-700 hover:underline">
                                        View all
                                    </button>
                                @endif
                            </div>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                @foreach ($product->specifications->take(6) as $specification)
                                    <div class="flex gap-2.5 border border-neutral-200 p-2.5">
                                        <div class="flex h-7 w-7 shrink-0 items-center justify-center bg-red-50 text-xs text-red-700">
                                            <i class="{{ $specification->icon ?: 'fa-solid fa-circle-check' }}"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-[10px] font-semibold uppercase tracking-wide text-neutral-500 leading-tight">{{ $specification->label }}</div>
                                            <div class="mt-0.5 text-xs font-semibold leading-4 text-neutral-900">{{ $specification->value }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Variants --}}
                    @if ($product->variants->isNotEmpty())
                        <div class="mt-5 border-t border-neutral-100 pt-5">
                            <h2 class="text-xs font-bold uppercase tracking-wide text-neutral-950">Available Options</h2>
                            <div class="mt-3 grid gap-2">
                                <label class="block cursor-pointer">
                                    <input type="radio" name="variant_selector" value="" class="peer sr-only"
                                           data-variant-label="Default Product"
                                           @checked($selectedVariantId === '')>
                                    <span class="block border border-neutral-200 bg-white p-3 text-sm transition hover:border-red-400 peer-checked:border-red-600 peer-checked:bg-red-50">
                                        <span class="block font-semibold text-neutral-950">Default Product</span>
                                        <span class="mt-1 block text-xs leading-5 text-neutral-500">General catalogue enquiry without a specific option.</span>
                                    </span>
                                </label>

                                @foreach ($product->variants as $variant)
                                    <label class="block {{ $variant->stock < 1 ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                                        <input type="radio" name="variant_selector" value="{{ $variant->id }}"
                                               class="peer sr-only"
                                               data-variant-label="{{ $variant->variant_name }}: {{ $variant->variant_value }}"
                                               @checked($selectedVariantId === (string) $variant->id)
                                               @disabled($variant->stock < 1)>
                                        <span class="grid min-h-[4.5rem] gap-2 border border-neutral-200 bg-white p-3 text-sm transition hover:border-red-400 peer-checked:border-red-600 peer-checked:bg-red-50 sm:grid-cols-[1fr_auto]">
                                            <span>
                                                <span class="block text-[11px] font-semibold uppercase tracking-wide text-neutral-500">{{ $variant->variant_name }}</span>
                                                <span class="mt-1 block font-semibold text-neutral-950">{{ $variant->variant_value }}</span>
                                            </span>
                                            <span class="text-left sm:text-right">
                                                @if ($variant->price)
                                                    <span class="block font-semibold text-neutral-900">Rs.&nbsp;{{ number_format((float) $variant->price, 2) }}</span>
                                                @endif
                                                <span class="block text-xs font-semibold {{ $variant->stock > 0 ? 'text-green-700' : 'text-red-600' }}">
                                                    {{ $variant->stock > 0 ? 'Available' : 'Out of stock' }}
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>{{-- / Product info card --}}

                {{-- Enquiry form --}}
                <div id="product-enquiry" class="mt-4 border border-red-200 bg-white p-5 sm:p-6">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-red-700">Primary Action</p>
                    <h2 class="mt-2 text-xl font-bold text-neutral-950">Send Product Enquiry</h2>
                    <p class="mt-2 text-sm leading-6 text-neutral-600">Share quantity, branding requirement or delivery timeline. Our team will confirm availability and quotation details.</p>

                    @if (session('success'))
                        <div class="mt-4 border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-4 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form id="product-enquiry-form" action="{{ route('product-enquiry.store') }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="enquiry_variant_id" value="{{ old('variant_id') }}">
                        <input type="hidden" id="selected_color_label" value="">

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="customer_name" class="mb-1 block text-sm font-semibold text-neutral-700">Name</label>
                                <input type="text" id="customer_name" name="customer_name"
                                       value="{{ old('customer_name') }}"
                                       class="w-full border border-neutral-300 bg-white px-3 py-2.5 text-sm transition focus:border-red-500 focus:outline-none">
                                @error('customer_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="customer_phone" class="mb-1 block text-sm font-semibold text-neutral-700">Phone</label>
                                <input type="text" id="customer_phone" name="customer_phone"
                                       value="{{ old('customer_phone') }}"
                                       class="w-full border border-neutral-300 bg-white px-3 py-2.5 text-sm transition focus:border-red-500 focus:outline-none">
                                @error('customer_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="customer_email" class="mb-1 block text-sm font-semibold text-neutral-700">Email</label>
                            <input type="email" id="customer_email" name="customer_email"
                                   value="{{ old('customer_email') }}"
                                   class="w-full border border-neutral-300 bg-white px-3 py-2.5 text-sm transition focus:border-red-500 focus:outline-none">
                            @error('customer_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="message" class="mb-1 block text-sm font-semibold text-neutral-700">Requirement / Message</label>
                            <textarea id="message" name="message" rows="4"
                                      data-product-name="{{ $product->name }}"
                                      data-product-sku="{{ $product->sku }}"
                                      class="w-full border border-neutral-300 bg-white px-3 py-2.5 text-sm transition focus:border-red-500 focus:outline-none">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <button type="submit" class="bg-neutral-950 px-5 py-3 text-sm font-bold text-white transition hover:bg-neutral-800">
                                Send Enquiry
                            </button>
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                               class="border border-green-600 px-5 py-3 text-center text-sm font-bold text-green-700 transition hover:bg-green-50">
                                <i class="fa-brands fa-whatsapp mr-2"></i>WhatsApp
                            </a>
                        </div>
                    </form>

                    {{-- Cart add-on --}}
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-4 border border-neutral-200 bg-neutral-50 p-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="cart_variant_id" value="{{ old('variant_id') }}">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <input type="number" name="quantity" min="1" max="{{ max($product->stock, 1) }}"
                                   value="{{ old('quantity', 1) }}"
                                   class="w-full border border-neutral-300 bg-white px-3 py-2 text-sm focus:border-red-500 focus:outline-none sm:w-24">
                            <button type="submit"
                                    @disabled($product->stock < 1)
                                    class="border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-100 disabled:cursor-not-allowed disabled:bg-neutral-100 disabled:text-neutral-400">
                                {{ $product->stock > 0 ? 'Add To Cart' : 'Out Of Stock' }}
                            </button>
                        </div>
                    </form>
                </div>{{-- / Enquiry form --}}

            </aside>{{-- / RIGHT --}}
        </div>{{-- / Main product grid --}}

        {{-- ── Tabs section: Description / Specifications / Branding & Packaging ── --}}
        @if ($firstTab)
        <div class="mt-8" id="product-tabs">

            {{-- Tab nav --}}
            <div class="flex overflow-x-auto border-b-2 border-neutral-200 bg-white scrollbar-hide">
                @if ($hasSpecsTab)
                    <button type="button" data-tab-btn="specifications"
                            class="product-tab-btn -mb-[2px] shrink-0 border-b-2 px-5 py-3.5 text-sm font-semibold transition
                                   {{ $firstTab === 'specifications' ? 'border-red-600 text-red-700' : 'border-transparent text-neutral-600 hover:text-neutral-900' }}">
                        <i class="fa-solid fa-list-check mr-2 {{ $firstTab === 'specifications' ? 'text-red-500' : 'text-neutral-400' }}"></i>Specifications
                    </button>
                @endif
                @if ($hasBrandingTab)
                    <button type="button" data-tab-btn="branding"
                            class="product-tab-btn -mb-[2px] shrink-0 border-b-2 px-5 py-3.5 text-sm font-semibold transition
                                   {{ $firstTab === 'branding' ? 'border-red-600 text-red-700' : 'border-transparent text-neutral-600 hover:text-neutral-900' }}">
                        <i class="fa-solid fa-print mr-2 {{ $firstTab === 'branding' ? 'text-red-500' : 'text-neutral-400' }}"></i>Branding &amp; Packaging
                    </button>
                @endif
            </div>

            {{-- Specifications panel --}}
            @if ($hasSpecsTab)
            <div data-tab-panel="specifications"
                 class="{{ $firstTab !== 'specifications' ? 'hidden' : '' }} border border-t-0 border-neutral-200 bg-white p-5 sm:p-8">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="border-b-2 border-neutral-200 bg-neutral-50 text-left">
                                <th class="w-10 py-3 pl-4 pr-3 text-[11px] font-semibold uppercase tracking-wide text-neutral-500"></th>
                                <th class="py-3 pr-4 text-[11px] font-semibold uppercase tracking-wide text-neutral-500">Specification</th>
                                <th class="py-3 pr-4 text-[11px] font-semibold uppercase tracking-wide text-neutral-500">Value / Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            @foreach ($product->specifications as $spec)
                            <tr class="hover:bg-neutral-50">
                                <td class="py-3 pl-4 pr-3">
                                    <span class="flex h-6 w-6 items-center justify-center bg-red-50 text-[11px] text-red-600">
                                        <i class="{{ $spec->icon ?: 'fa-solid fa-circle-check' }}"></i>
                                    </span>
                                </td>
                                <td class="py-3 pr-4 font-semibold text-neutral-800">{{ $spec->label }}</td>
                                <td class="py-3 pr-4 text-neutral-700">{{ $spec->value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Branding & Packaging panel --}}
            @if ($hasBrandingTab)
            <div data-tab-panel="branding"
                 class="{{ $firstTab !== 'branding' ? 'hidden' : '' }} border border-t-0 border-neutral-200 bg-white p-5 sm:p-8">
                <div class="grid gap-8 md:grid-cols-2">

                    @if ($hasBrandingSection)
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-red-700">Customization</p>
                        <h3 class="mt-2 text-xl font-bold text-neutral-950">Branding &amp; Printing</h3>

                        @if ($product->branding_details)
                            <div class="mt-4 text-[15px] leading-8 text-neutral-700 [&_li]:my-1.5 [&_p]:mb-4 [&_ul]:list-disc [&_ul]:pl-5">
                                {!! $product->branding_details !!}
                            </div>
                        @endif

                        @if ($product->printing_methods)
                            <div class="mt-4 border border-neutral-200 bg-neutral-50 p-4">
                                <div class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">Printing Methods</div>
                                <div class="mt-1 text-sm leading-6 text-neutral-800">{{ $product->printing_methods }}</div>
                            </div>
                        @endif

                        @if ($brandingImages->isNotEmpty())
                            <div class="mt-4 grid grid-cols-3 gap-3">
                                @foreach ($brandingImages as $brandingImage)
                                    <img src="{{ $brandingImage['src'] }}" alt="{{ $brandingImage['alt'] }}"
                                         class="aspect-square w-full border border-neutral-200 bg-neutral-50 object-contain p-2">
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif

                    @if ($hasPackagingSection)
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-red-700">Fulfilment</p>
                        <h3 class="mt-2 text-xl font-bold text-neutral-950">Packaging &amp; Production</h3>

                        @if ($product->packaging_details)
                            <div class="mt-4 text-[15px] leading-8 text-neutral-700 [&_li]:my-1.5 [&_p]:mb-4 [&_ul]:list-disc [&_ul]:pl-5">
                                {!! $product->packaging_details !!}
                            </div>
                        @endif

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @if ($product->moq)
                                <div class="border border-neutral-200 bg-neutral-50 p-4">
                                    <div class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">MOQ</div>
                                    <div class="mt-1 text-sm font-semibold text-neutral-900">{{ $product->moq }}</div>
                                </div>
                            @endif
                            @if ($product->production_timeline)
                                <div class="border border-neutral-200 bg-neutral-50 p-4">
                                    <div class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">Production Timeline</div>
                                    <div class="mt-1 text-sm font-semibold text-neutral-900">{{ $product->production_timeline }}</div>
                                </div>
                            @endif
                        </div>

                        @if ($packagingImages->isNotEmpty())
                            <div class="mt-4 grid grid-cols-3 gap-3">
                                @foreach ($packagingImages as $packagingImage)
                                    <img src="{{ $packagingImage['src'] }}" alt="{{ $packagingImage['alt'] }}"
                                         class="aspect-square w-full border border-neutral-200 bg-neutral-50 object-contain p-2">
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>
            @endif

        </div>{{-- / Tabs --}}
        @endif

    </div>
</section>

{{-- ── Related products carousel ── --}}
@if ($relatedProducts->isNotEmpty())
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-5 flex items-center justify-between border-b border-neutral-200 pb-4">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-red-700">More From This Range</p>
                <h2 class="mt-2 text-2xl font-bold text-neutral-950">Related Products</h2>
            </div>
            <div class="flex shrink-0 gap-2">
                <button type="button" class="related-prev flex h-9 w-9 items-center justify-center border border-neutral-200 text-neutral-600 transition hover:border-red-500 hover:text-red-600" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button type="button" class="related-next flex h-9 w-9 items-center justify-center border border-neutral-200 text-neutral-600 transition hover:border-red-500 hover:text-red-600" aria-label="Next">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>
            </div>
        </div>
        
        {{-- Grid for desktop (4 cards), flex for mobile/tablet scroll --}}
        <div class="related-scroll grid auto-cols-fr grid-flow-col gap-5 overflow-x-auto pb-2 scrollbar-hide snap-x snap-mandatory lg:grid-cols-4 lg:overflow-visible">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="w-[260px] shrink-0 snap-start sm:w-[280px] lg:w-auto">
                    @include('frontend.partials.product-card', ['product' => $relatedProduct])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
(() => {
    const mainImg       = document.getElementById('main-product-image');
    const enquiryVariant = document.getElementById('enquiry_variant_id');
    const cartVariant    = document.getElementById('cart_variant_id');
    const selectedColor  = document.getElementById('selected_color_label');
    const message        = document.getElementById('message');
    const enquiryForm    = document.getElementById('product-enquiry-form');

    /* ── Gallery thumbnail switcher ── */
    function setActiveThumbnail(src) {
        document.querySelectorAll('[data-gallery-thumbnail]').forEach((t) => {
            const active = t.dataset.gallerySrc === src;
            t.classList.toggle('border-red-600',     active);
            t.classList.toggle('border-neutral-200', !active);
        });
    }

    document.querySelectorAll('[data-gallery-thumbnail]').forEach((btn) => {
        btn.addEventListener('click', () => {
            if (!mainImg) return;
            mainImg.src = btn.dataset.gallerySrc;
            setActiveThumbnail(btn.dataset.gallerySrc);
        });
    });

    /* ── Color swatch → switch image ── */
    function markColor(btn) {
        document.querySelectorAll('.product-color-option').forEach((opt) => {
            opt.classList.remove('border-red-600', 'bg-red-50', 'text-red-700');
            opt.classList.add('border-neutral-200', 'bg-white', 'text-neutral-700');
        });
        btn.classList.remove('border-neutral-200', 'bg-white', 'text-neutral-700');
        btn.classList.add('border-red-600', 'bg-red-50', 'text-red-700');

        if (selectedColor) selectedColor.value = btn.dataset.colorName || '';

        const colorImg = btn.dataset.colorImage;
        if (colorImg && mainImg) {
            mainImg.src = colorImg;
            setActiveThumbnail(colorImg);
        }
    }

    document.querySelectorAll('.product-color-option').forEach((btn, i) => {
        btn.addEventListener('click', () => markColor(btn));
        if (i === 0) markColor(btn);
    });

    /* ── Variant sync ── */
    function syncVariant() {
        const checked = document.querySelector('input[name="variant_selector"]:checked');
        const val = checked ? checked.value : '';
        if (enquiryVariant) enquiryVariant.value = val;
        if (cartVariant)    cartVariant.value    = val;
    }
    document.querySelectorAll('input[name="variant_selector"]').forEach((inp) => {
        inp.addEventListener('change', syncVariant);
    });
    syncVariant();

    /* ── Enquiry message auto-append ── */
    if (message) {
        message.dataset.baseMessage = message.value;
        message.addEventListener('input', () => { message.dataset.baseMessage = message.value; });
    }
    enquiryForm?.addEventListener('submit', () => {
        if (!message) return;
        const checked = document.querySelector('input[name="variant_selector"]:checked');
        const base    = (message.dataset.baseMessage || '').trim();
        const details = [];
        if (message.dataset.productName) details.push(`Product: ${message.dataset.productName}`);
        if (message.dataset.productSku)  details.push(`SKU: ${message.dataset.productSku}`);
        if (checked?.dataset.variantLabel && checked.value) details.push(`Selected option: ${checked.dataset.variantLabel}`);
        if (selectedColor?.value) details.push(`Selected color: ${selectedColor.value}`);
        message.value = details.length
            ? [base, `Product enquiry details:\n${details.join('\n')}`].filter(Boolean).join('\n\n')
            : base;
    });

    /* ── Tab switching ── */
    const tabBtns   = document.querySelectorAll('.product-tab-btn');
    const tabPanels = document.querySelectorAll('[data-tab-panel]');

    function activateTab(id) {
        tabBtns.forEach((btn) => {
            const on = btn.dataset.tabBtn === id;
            btn.classList.toggle('border-red-600',      on);
            btn.classList.toggle('text-red-700',        on);
            btn.classList.toggle('border-transparent',  !on);
            btn.classList.toggle('text-neutral-600',    !on);
            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('text-red-500',    on);
                icon.classList.toggle('text-neutral-400', !on);
            }
        });
        tabPanels.forEach((p) => p.classList.toggle('hidden', p.dataset.tabPanel !== id));
    }

    tabBtns.forEach((btn) => btn.addEventListener('click', () => activateTab(btn.dataset.tabBtn)));

    /* "View all" link jumps to specifications tab */
    document.querySelectorAll('[data-open-tab]').forEach((el) => {
        el.addEventListener('click', () => {
            activateTab(el.dataset.openTab);
            document.getElementById('product-tabs')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    /* ── Related products carousel ── */
    const relPrev   = document.querySelector('.related-prev');
    const relNext   = document.querySelector('.related-next');
    const relScroll = document.querySelector('.related-scroll');
    relPrev?.addEventListener('click', () => relScroll?.scrollBy({ left: -340, behavior: 'smooth' }));
    relNext?.addEventListener('click', () => relScroll?.scrollBy({ left:  340, behavior: 'smooth' }));

    /* ── Description read-more toggle ── */
    const descWrap   = document.getElementById('product-desc-wrap');
    const descFade   = document.getElementById('product-desc-fade');
    const descToggle = document.getElementById('product-desc-toggle');
    if (descWrap && descToggle) {
        let expanded = false;
        descToggle.addEventListener('click', () => {
            expanded = !expanded;
            descWrap.style.maxHeight = expanded ? 'none' : '9rem';
            if (descFade) descFade.classList.toggle('hidden', expanded);
            descToggle.innerHTML = expanded
                ? 'Read less <i class="fa-solid fa-chevron-up text-[10px]"></i>'
                : 'Read more <i class="fa-solid fa-chevron-down text-[10px]"></i>';
        });
        setTimeout(() => {
            if (descWrap.scrollHeight <= descWrap.offsetHeight + 4) {
                descWrap.style.maxHeight = 'none';
                if (descFade) descFade.classList.add('hidden');
                descToggle.style.display = 'none';
            }
        }, 0);
    }

    /* ── Mobile sticky bar: hide once enquiry section is visible ── */
    const stickyBar     = document.getElementById('mobile-sticky-cta');
    const enquirySection = document.getElementById('product-enquiry');
    if (stickyBar && enquirySection && 'IntersectionObserver' in window) {
        new IntersectionObserver((entries) => {
            stickyBar.style.display = entries[0].isIntersecting ? 'none' : '';
        }, { threshold: 0.2 }).observe(enquirySection);
    }
})();
</script>
@endpush
