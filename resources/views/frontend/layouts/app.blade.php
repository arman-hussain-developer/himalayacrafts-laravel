@php
$siteName = $frontendSettings->get('site_name') ?: config('app.name', 'Stationery Store');
$logo = $frontendSettings->get('logo');
$phone = $frontendSettings->get('site_phone');
$whatsappUrl = \App\Support\WhatsApp::bulkQuoteUrl();
$fallbackAboutPage = $frontendHeaderMenus->isEmpty()
    ? \App\Models\Page::where('status', true)->where('slug', 'about-us')->first()
    : null;
$headerMenus = $frontendHeaderMenus->isNotEmpty()
    ? $frontendHeaderMenus
    : collect([
        ['label' => 'Home', 'type' => 'route', 'url' => route('home'), 'open_in_new_tab' => false, 'is_mega' => false, 'children' => collect()],
        ['label' => 'Catalog', 'type' => 'route', 'url' => route('products.index'), 'open_in_new_tab' => false, 'is_mega' => false, 'children' => collect()],
        ['label' => 'About', 'type' => 'page', 'url' => $fallbackAboutPage ? route('pages.about') : url('/about'), 'open_in_new_tab' => false, 'is_mega' => false, 'children' => collect()],
        ['label' => 'Categories', 'type' => 'category', 'url' => route('products.index'), 'open_in_new_tab' => false, 'is_mega' => true, 'children' => collect()],
        ['label' => 'Bulk Quote', 'type' => 'route', 'url' => route('bulk-quote.create'), 'open_in_new_tab' => false, 'is_mega' => false, 'children' => collect()],
        ['label' => 'Contact', 'type' => 'route', 'url' => route('contact'), 'open_in_new_tab' => false, 'is_mega' => false, 'children' => collect()],
    ]);
$menuUrl = fn ($menu) => $menu instanceof \App\Models\NavigationMenu ? $menu->resolved_url : data_get($menu, 'url', '#');
$menuChildren = fn ($menu) => $menu instanceof \App\Models\NavigationMenu ? $menu->activeChildren : collect(data_get($menu, 'children', []));
$menuTarget = fn ($menu) => data_get($menu, 'open_in_new_tab') ? '_blank' : '_self';
$menuRel = fn ($menu) => data_get($menu, 'open_in_new_tab') ? 'noopener' : null;
$isMenuActive = fn ($menu) => rtrim($menuUrl($menu), '/') === rtrim(url()->current(), '/');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', $siteName . ' corporate gifts and catalogue enquiry website')">
    <title>@yield('title', $siteName)</title>
    <link rel="canonical" href="{{ url()->current() }}">

    @if ($frontendSettings->get('favicon'))
    <link rel="icon" href="{{ asset($frontendSettings->get('favicon')) }}">
    @endif

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-dark text-neutral-900 font-sans overflow-x-hidden">

    <div class="h-8 overflow-hidden bg-red-600 text-white sm:h-9">
        <div class="relative mx-auto h-full max-w-7xl">
            <div class="flex h-full items-center gap-10 whitespace-nowrap pl-4 text-[11px] font-semibold uppercase tracking-wide animate-marquee sm:gap-12 sm:pl-6 sm:text-xs">

                <span class="flex shrink-0 items-center gap-2">
                    <i class="fa-solid fa-briefcase text-white/90"></i>
                    CORPORATE CATALOGUE ENQUIRIES
                </span>

                <span class="flex shrink-0 items-center gap-2">
                    <i class="fa-solid fa-gift text-white/90"></i>
                    BULK GIFTING AND BRANDING AVAILABLE
                </span>

                <span class="flex shrink-0 items-center gap-2">
                    <i class="fa-solid fa-headset text-white/90"></i>
                    SEND ENQUIRY FOR BEST QUOTE
                </span>

                <span class="flex shrink-0 items-center gap-2">
                    <i class="fa-solid fa-briefcase text-white/90"></i>
                    CORPORATE CATALOGUE ENQUIRIES
                </span>

                <span class="flex shrink-0 items-center gap-2">
                    <i class="fa-solid fa-gift text-white/90"></i>
                    BULK GIFTING AND BRANDING AVAILABLE
                </span>
            </div>
        </div>
    </div>

    <header class="sticky top-0 z-50 bg-white shadow-sm">
        <div class="mx-auto w-screen max-w-none px-4 sm:px-6 lg:w-auto lg:max-w-7xl lg:px-8">

            <div class="hidden min-h-[96px] grid-cols-3 items-center gap-4 lg:grid">
                <div class="hidden text-sm text-neutral-500 lg:block">
                    Customer Service
                    <span class="ml-1 font-semibold text-neutral-800">
                        {{ $phone ?: '+91 0000000000' }}
                    </span>
                </div>

                <a href="{{ route('home') }}" class="flex items-center justify-start lg:justify-center">
                    @if ($logo)
                    <img src="{{ asset($logo) }}" alt="{{ $siteName }}" class="h-20 max-w-[190px] object-contain">
                    @else
                    <span class="rounded-lg border-2 border-red-600 px-5 py-2 text-xl font-black text-red-600">
                        {{ $siteName }}
                    </span>
                    @endif
                </a>

                <div class="flex items-center justify-end gap-3">
                    <form action="{{ route('products.index') }}" method="GET" class="hidden w-64 items-center border-b border-neutral-300 lg:flex">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="w-full bg-transparent py-2 text-sm outline-none">
                        <button type="submit" class="text-neutral-500 hover:text-red-600">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="hidden rounded-full bg-green-600 px-4 py-2 text-xs font-black uppercase tracking-wide text-white hover:bg-green-700 lg:inline-flex">
                        <i class="fa-brands fa-whatsapp mr-2"></i>
                        WhatsApp
                    </a>

                    <a href="{{ route('bulk-quote.create') }}" class="hidden rounded-full bg-neutral-950 px-4 py-2 text-xs font-black uppercase tracking-wide text-white hover:bg-neutral-800 lg:inline-flex">
                        Bulk Quote
                    </a>

                    <a href="{{ route('cart.index') }}" class="relative text-lg text-neutral-500 hover:text-red-600" title="Cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="absolute -right-3 -top-3 flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-[11px] font-bold text-red-600">{{ $cartCount }}</span>
                    </a>
                </div>
            </div>

            <div class="lg:hidden">
                <div class="relative flex h-16 items-center">
                    <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center pr-14 sm:pr-24">
                        @if ($logo)
                        <img src="{{ asset($logo) }}" alt="{{ $siteName }}" class="h-10 max-w-[170px] object-contain sm:h-11 sm:max-w-[210px]">
                        @else
                        <span class="max-w-[190px] truncate rounded-md border-2 border-red-600 px-3 py-1.5 text-base font-black text-red-600 sm:max-w-[240px]">
                            {{ $siteName }}
                        </span>
                        @endif
                    </a>

                    <div class="absolute right-0 top-1/2 flex shrink-0 -translate-y-1/2 items-center gap-2">
                        <a href="{{ route('cart.index') }}" class="relative hidden h-10 w-10 items-center justify-center rounded-full border border-neutral-200 text-neutral-600 hover:border-red-200 hover:text-red-600 sm:flex" title="Cart">
                            <i class="fa-solid fa-cart-shopping text-sm"></i>
                            <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white">{{ $cartCount }}</span>
                        </a>

                        <button type="button" id="mobile-menu-toggle" class="flex h-10 w-10 items-center justify-center rounded-full border border-neutral-200 text-neutral-800 hover:border-red-200 hover:text-red-600" aria-controls="mobile-menu" aria-expanded="false" aria-label="Open menu">
                            <i class="fa-solid fa-bars text-lg" data-menu-open-icon></i>
                            <i class="fa-solid fa-xmark hidden text-lg" data-menu-close-icon></i>
                        </button>
                    </div>
                </div>

                <form action="{{ route('products.index') }}" method="GET" class="mb-3 flex h-11 items-center rounded-full border border-neutral-200 bg-neutral-50 px-4 shadow-sm">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search catalog..." class="min-w-0 flex-1 bg-transparent text-sm outline-none placeholder:text-neutral-400">
                    <button type="submit" class="ml-2 text-neutral-500 hover:text-red-600" aria-label="Search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <div id="mobile-menu" class="hidden border-t border-neutral-100 pb-4 pt-3">
                    <div class="grid gap-1 text-sm font-bold text-neutral-800">
                        @foreach ($headerMenus as $menu)
                            @php
                                $children = $menuChildren($menu);
                                $isMega = (bool) data_get($menu, 'is_mega');
                            @endphp

                            @if ($isMega)
                                <details class="hc-mobile-category-accordion">
                                    <summary>
                                        <span>{{ data_get($menu, 'label') }}</span>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </summary>
                                    <div class="hc-mobile-category-accordion__panel">
                                        @foreach ($frontendCategories as $category)
                                            <details class="hc-mobile-category-accordion__group">
                                                <summary>
                                                    <span>{{ $category->name }}</span>
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </summary>
                                                <div class="hc-mobile-category-accordion__links">
                                                    <a href="{{ route('products.category', $category->slug) }}" data-mobile-menu-link>
                                                        All {{ $category->name }}
                                                    </a>
                                                    @foreach ($category->subCategories as $subCategory)
                                                        <a href="{{ route('products.index', ['category' => $category->slug, 'sub_category' => $subCategory->slug]) }}" data-mobile-menu-link>
                                                            {{ $subCategory->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </details>
                                        @endforeach
                                    </div>
                                </details>
                            @elseif ($children->isNotEmpty())
                                <details class="rounded-xl border border-neutral-200 bg-white">
                                    <summary class="cursor-pointer px-3 py-3 text-sm font-black uppercase tracking-wide text-neutral-900">
                                        {{ data_get($menu, 'label') }}
                                    </summary>
                                    <div class="grid gap-1 border-t border-neutral-100 px-3 py-2">
                                        @foreach ($children as $child)
                                            <a href="{{ $menuUrl($child) }}" target="{{ $menuTarget($child) }}" @if($menuRel($child)) rel="{{ $menuRel($child) }}" @endif data-mobile-menu-link class="rounded px-3 py-2 text-sm font-medium text-neutral-600 hover:bg-red-50 hover:text-red-600">
                                                {{ data_get($child, 'label') }}
                                            </a>
                                        @endforeach
                                    </div>
                                </details>
                            @else
                                <a href="{{ $menuUrl($menu) }}" target="{{ $menuTarget($menu) }}" @if($menuRel($menu)) rel="{{ $menuRel($menu) }}" @endif data-mobile-menu-link class="rounded-lg px-3 py-2.5 {{ $isMenuActive($menu) ? 'bg-red-50 text-red-600' : 'hover:bg-red-50 hover:text-red-600' }}">
                                    {{ data_get($menu, 'label') }}
                                </a>
                            @endif
                        @endforeach

                        <a href="{{ $whatsappUrl }}" data-mobile-menu-link target="_blank" rel="noopener" class="rounded-lg px-3 py-2.5 text-green-700 hover:bg-green-50">
                            <i class="fa-brands fa-whatsapp mr-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <nav class="hidden border-t border-neutral-100 lg:block">
                <div class="relative flex items-center justify-center gap-x-7 py-4 text-[12px] font-extrabold uppercase tracking-wide text-neutral-800">
                    @foreach ($headerMenus as $menu)
                        @php
                            $children = $menuChildren($menu);
                            $isMega = (bool) data_get($menu, 'is_mega');
                            $activeClass = $isMenuActive($menu) ? 'text-red-600' : 'text-neutral-800';
                        @endphp

                        @if ($isMega)
                            <div class="group relative">
                                <button type="button" class="inline-flex items-center gap-2 rounded-full px-2 py-1 transition {{ $activeClass }} hover:bg-red-50 hover:text-red-600">
                                    {{ data_get($menu, 'label') }}
                                    <i class="fa-solid fa-chevron-down text-[10px] transition group-hover:rotate-180"></i>
                                </button>

                                <div class="hc-mega-menu invisible absolute left-1/2 top-full z-50 -translate-x-1/2 translate-y-2 text-left opacity-0 transition duration-150 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                                    <div class="hc-mega-menu__inner">
                                    <div class="hc-mega-menu__header">
                                        <div>
                                            <div class="hc-mega-menu__heading">Browse Corporate Catalog</div>
                                            <div class="hc-mega-menu__subtitle">Categories and subcategories for bulk enquiries</div>
                                        </div>
                                    </div>
                                    <div class="hc-mega-menu__grid">
                                        @foreach ($frontendCategories as $category)
                                            <div class="hc-mega-menu__group">
                                                <a href="{{ route('products.category', $category->slug) }}" class="hc-mega-menu__title">
                                                    {{ $category->name }}
                                                </a>

                                                @if ($category->subCategories->isNotEmpty())
                                                    <div class="hc-mega-menu__links">
                                                        @foreach ($category->subCategories as $subCategory)
                                                            <a href="{{ route('products.index', ['category' => $category->slug, 'sub_category' => $subCategory->slug]) }}" class="hc-mega-menu__link">
                                                                {{ $subCategory->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($children->isNotEmpty())
                            <div class="group relative">
                                <button type="button" class="inline-flex items-center gap-2 rounded-full px-2 py-1 transition {{ $activeClass }} hover:bg-red-50 hover:text-red-600">
                                    {{ data_get($menu, 'label') }}
                                    <i class="fa-solid fa-chevron-down text-[10px] transition group-hover:rotate-180"></i>
                                </button>

                                <div class="invisible absolute left-1/2 top-full z-50 w-56 -translate-x-1/2 translate-y-2 rounded-lg border border-neutral-200 bg-white p-2 text-left shadow-xl opacity-0 transition duration-150 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                                    @foreach ($children as $child)
                                        <a href="{{ $menuUrl($child) }}" target="{{ $menuTarget($child) }}" @if($menuRel($child)) rel="{{ $menuRel($child) }}" @endif class="block rounded px-3 py-2 text-sm font-bold normal-case tracking-normal text-neutral-700 hover:bg-red-50 hover:text-red-600">
                                            {{ data_get($child, 'label') }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $menuUrl($menu) }}" target="{{ $menuTarget($menu) }}" @if($menuRel($menu)) rel="{{ $menuRel($menu) }}" @endif class="rounded-full px-2 py-1 transition {{ $activeClass }} hover:bg-red-50 hover:text-red-600">
                                {{ data_get($menu, 'label') }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </nav>

        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="fixed bottom-5 right-5 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-green-600 text-2xl text-white shadow-xl ring-4 ring-white hover:bg-green-700" aria-label="Chat on WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <footer class="mt-16 bg-neutral-950 text-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <div class="text-2xl font-black text-white">
                        
                           <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center pr-14 sm:pr-24">
                        @if ($logo)
                        <img src="{{ asset($logo) }}" alt="{{ $siteName }}" class="h-10 max-w-[170px] object-contain sm:h-11 sm:max-w-[210px]">
                        @else
                        <span class="max-w-[190px] truncate rounded-md border-2 border-red-600 px-3 py-1.5 text-base font-black text-red-600 sm:max-w-[240px]">
                            {{ $siteName }}
                        </span>
                        @endif
                    </a>
                        </div>
                    <p class="mt-4 max-w-sm text-sm leading-7 text-neutral-400">
                        Corporate gifts, stationery, bags, diaries, bottles and promotional products for bulk catalogue enquiries.
                    </p>

                    <div class="mt-6 flex items-center gap-3">
                        @if ($frontendSettings->get('facebook_url'))
                        <a href="{{ $frontendSettings->get('facebook_url') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 text-neutral-300 ring-1 ring-white/10 transition hover:-translate-y-0.5 hover:bg-red-600 hover:text-white">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        @endif

                        @if ($frontendSettings->get('instagram_url'))
                        <a href="{{ $frontendSettings->get('instagram_url') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 text-neutral-300 ring-1 ring-white/10 transition hover:-translate-y-0.5 hover:bg-red-600 hover:text-white">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        @endif

                        @if ($frontendSettings->get('youtube_url'))
                        <a href="{{ $frontendSettings->get('youtube_url') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 text-neutral-300 ring-1 ring-white/10 transition hover:-translate-y-0.5 hover:bg-red-600 hover:text-white">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-black uppercase tracking-[0.22em] text-neutral-400">CATEGORIES</h3>
                    <div class="mt-5 grid gap-3 text-sm text-neutral-300">
                        @foreach ($frontendCategories->take(6) as $category)
                        <a href="{{ route('products.category', $category->slug) }}" class="transition hover:text-red-400">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-black uppercase tracking-[0.22em] text-neutral-400">Quick Links</h3>
                    <div class="mt-5 grid gap-3 text-sm text-neutral-300">
                        @forelse ($frontendFooterMenus as $footerMenu)
                            <a href="{{ $menuUrl($footerMenu) }}" target="{{ $menuTarget($footerMenu) }}" @if($menuRel($footerMenu)) rel="{{ $menuRel($footerMenu) }}" @endif class="transition {{ $isMenuActive($footerMenu) ? 'text-red-400' : 'hover:text-red-400' }}">
                                {{ $footerMenu->label }}
                            </a>
                        @empty
                            <a href="{{ route('home') }}" class="transition hover:text-red-400">Home</a>
                            <a href="{{ route('products.index') }}" class="transition hover:text-red-400">Full Catalog</a>
                            <a href="{{ route('bulk-quote.create') }}" class="transition hover:text-red-400">Bulk Quote</a>
                            <a href="{{ route('contact') }}" class="transition hover:text-red-400">Contact Us</a>
                        @endforelse
                    </div>

                    <!--<div class="mt-6 space-y-3 text-sm text-neutral-400">-->
                    <!--    @if ($frontendSettings->get('site_phone'))-->
                    <!--    <div class="flex gap-3">-->
                    <!--        <i class="fa-solid fa-phone mt-1 text-red-500"></i>-->
                    <!--        <span>{{ $frontendSettings->get('site_phone') }}</span>-->
                    <!--    </div>-->
                    <!--    @endif-->

                    <!--    @if ($frontendSettings->get('site_email'))-->
                    <!--    <div class="flex gap-3">-->
                    <!--        <i class="fa-regular fa-envelope mt-1 text-red-500"></i>-->
                    <!--        <a href="mailto:{{ $frontendSettings->get('site_email') }}" class="hover:text-red-400">{{ $frontendSettings->get('site_email') }}</a>-->
                    <!--    </div>-->
                    <!--    @endif-->
                    <!--</div>-->
                </div>

                <div class="rounded-2xl bg-white/[0.06] p-5 ring-1 ring-white/10">
                    <h3 class="text-xs font-black uppercase tracking-[0.22em] text-neutral-400">Catalog Updates</h3>
                    <p class="mt-4 text-sm leading-7 text-neutral-300">
                        Need pricing, branding or bulk availability? Send us your requirement and quantity.
                    </p>

                    <div class="mt-5 flex overflow-hidden rounded-2xl bg-white shadow-sm">
                        <input type="email" placeholder="Email address" class="min-w-0 flex-1 bg-white px-4 py-3 text-sm text-neutral-900 outline-none placeholder:text-neutral-400">
                        <button type="button" class="bg-neutral-950 px-4 text-sm font-black text-white transition hover:bg-neutral-800">
                            Send
                        </button>
                    </div>

                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="mt-4 flex items-center justify-center gap-2 rounded-2xl bg-green-600 px-4 py-3 text-sm font-black text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700">
                        <i class="fa-brands fa-whatsapp"></i>
                        WhatsApp Enquiry
                    </a>

                    <!--@if ($frontendSettings->get('site_address'))-->
                    <!--<div class="mt-5 flex gap-3 text-sm leading-6 text-neutral-400">-->
                    <!--    <i class="fa-solid fa-location-dot mt-1 text-red-500"></i>-->
                    <!--    <span>{{ $frontendSettings->get('site_address') }}</span>-->
                    <!--</div>-->
                    <!--@endif-->
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 bg-black/20">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 text-center text-xs text-neutral-500 sm:px-6 md:flex-row lg:px-8">
                <div>Copyright © {{ date('Y') }} {{ $siteName }}. All Rights Reserved.</div>
                <!--<div class="font-semibold text-neutral-400">Catalog-first corporate gifting and stationery supply.</div>-->
            </div>
        </div>
    </footer>

    @stack('scripts')


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function bindScroller(prevSelector, nextSelector, scrollSelector, amount = 320) {
                const prev = document.querySelector(prevSelector);
                const next = document.querySelector(nextSelector);
                const scroller = document.querySelector(scrollSelector);

                if (!scroller) return;

                prev?.addEventListener('click', function() {
                    scroller.scrollBy({
                        left: -amount,
                        behavior: 'smooth'
                    });
                });

                next?.addEventListener('click', function() {
                    scroller.scrollBy({
                        left: amount,
                        behavior: 'smooth'
                    });
                });
            }

            bindScroller('.cat-strip-prev', '.cat-strip-next', '.cat-strip-scroll', 260);
            bindScroller('.popular-prev', '.popular-next', '.popular-scroll', 340);
            bindScroller('.latest-prev', '.latest-next', '.latest-scroll', 340);
            bindScroller('.testimonial-prev', '.testimonial-next', '.testimonial-scroll', 400);

            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const openIcon = document.querySelector('[data-menu-open-icon]');
            const closeIcon = document.querySelector('[data-menu-close-icon]');

            function setMobileMenu(open) {
                if (!mobileMenu || !mobileMenuToggle) return;

                mobileMenu.classList.toggle('hidden', !open);
                mobileMenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                mobileMenuToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                openIcon?.classList.toggle('hidden', open);
                closeIcon?.classList.toggle('hidden', !open);
            }

            mobileMenuToggle?.addEventListener('click', function() {
                setMobileMenu(mobileMenu?.classList.contains('hidden'));
            });

            document.querySelectorAll('[data-mobile-menu-link]').forEach(function(link) {
                link.addEventListener('click', function() {
                    setMobileMenu(false);
                });
            });
        });
    </script>
</body>

</html>
