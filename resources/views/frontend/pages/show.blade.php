@extends('frontend.layouts.app')

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?: $page->title)

@section('content')

@if ($page->slug === 'about-us')
@php
    $categoryUrl  = fn($slug) => route('products.category', $slug);
    $catalogueUrl = route('products.index');
    $fallback     = asset('uploads/ui/about-catalog-banner.svg');

    $hardCats = [
        ['name' => 'Premium Notebooks',      'slug' => 'notebooks',        'desc' => 'Diaries, executive journals, planners and branded notebooks.'],
        ['name' => 'Executive Pens',         'slug' => 'pens',             'desc' => 'Metal ball pens, roller pens and gift pen sets with logo printing.'],
        ['name' => 'Wallets & Purses',       'slug' => 'wallets',          'desc' => 'Leather wallets, ladies purses and travel pouches for gifting.'],
        ['name' => 'Desk Accessories',       'slug' => 'desk-accessories', 'desc' => 'Organisers, paperweights, card holders and table-top utility.'],
        ['name' => 'Bar Sets',               'slug' => 'bar-sets',         'desc' => 'Glassware, cocktail kits and premium bar gifting.'],
        ['name' => 'Novelty & Custom Gifts', 'slug' => 'novelty',          'desc' => 'Festive hampers, branded merchandise and customised gifting.'],
    ];

    $displayCats = (!empty($categories) && count($categories) > 0)
        ? $categories->take(6)
        : collect($hardCats);

    $services = [
        ['icon' => 'fa-gift',        't' => 'Corporate Gifts',          'd' => 'Curated product ranges for clients, employees, events and seasonal gifting occasions.'],
        ['icon' => 'fa-boxes-stacked','t' => 'Bulk Catalogue Supply',   'd' => 'Category-led browsing with enquiry-first buying for business quantities.'],
        ['icon' => 'fa-pen-ruler',   't' => 'Branding & Customization', 'd' => 'Logo printing, engraving and packaging customization through the quote workflow.'],
        ['icon' => 'fa-headset',     't' => 'Responsive Enquiries',     'd' => 'Clear product, quantity and requirement collection for faster quote handling.'],
    ];

    $values = [
        ['n' => '01', 'icon' => 'fa-star',          't' => 'Quality & Elegance',  'd' => 'Curated products combining utility with finish, suited for premium corporate gifting.'],
        ['n' => '02', 'icon' => 'fa-tags',           't' => 'Competitive Pricing', 'd' => 'Quantity-based bulk pricing so larger orders make procurement-sense for your team.'],
        ['n' => '03', 'icon' => 'fa-truck-fast',     't' => 'Timely Delivery',     'd' => 'Lead times shared upfront on each enquiry so events and campaigns stay on schedule.'],
        ['n' => '04', 'icon' => 'fa-paintbrush',     't' => 'Customization',       'd' => 'Logo branding, colour, packaging and product combinations available on request.'],
    ];
@endphp
@section('content')
<section class="bg-neutral-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-neutral-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-red-600">Home</a>
            <span class="text-neutral-300">/</span>
            <span class="text-neutral-900">{{ $page->title }}</span>
        </nav>
        <div class="mt-8 max-w-3xl">
            <h1 class="text-3xl font-black text-neutral-950 sm:text-4xl lg:text-5xl">{{ $page->title }}</h1>
            @if ($page->meta_description)
                <p class="mt-5 text-base leading-8 text-neutral-600 sm:text-lg">{{ $page->meta_description }}</p>
            @endif
        </div>
    </div>
</section>


{{-- ═══════════════ BRAND INTRO ═══════════════ --}}
<section class="bg-white">
   
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8 lg:py-20">

        <article>
            <!--<div class="text-xs font-black uppercase tracking-widest text-red-600">Our Story</div>-->
            <h2 class="mt-4 text-2xl font-black leading-snug text-neutral-950 sm:text-3xl">
                Your Trusted Partner for Premium Corporate Gifting.
            </h2>

            @if ($page->content)
                <div class="mt-6 space-y-4 text-base leading-8 text-neutral-600 [&_a]:font-bold [&_a]:text-red-600 [&_li]:ml-5 [&_li]:list-disc [&_strong]:text-neutral-950">
                    {!! $page->content !!}
                </div>
            @else
                <div class="mt-6 space-y-4 text-base leading-8 text-neutral-600">
                    <p>Welcome to <strong class="text-neutral-950">Himalaya Crafts</strong> — your trusted partner for premium corporate gifting solutions. We specialize in supplying high-quality corporate gifts in bulk for employee gifting, client appreciation, promotional events, festive occasions, and brand promotions across India.</p>
                    <p>Our wide range covers premium notebooks, executive pens, wallets, purses, desk accessories, novelty items, bar sets, and customized gifting solutions — all designed to leave a lasting impression by combining quality, elegance, and utility.</p>
                    <p>Welcome to Himalaya Crafts, your trusted partner for premium corporate gifting solutions. We specialize in supplying high-quality corporate gifts in bulk for employee gifting, client appreciation, promotional events, festive occasions, and brand promotions. that reflect your brand identity and values. Our wide range of products includes premium notebooks, executive pens wallets, purses, desk accessories, novelty items, bar sets, and customized gifting solutions designed to leave a lasting impression. We focus on combining quality, elegance, and utility to deliver products With a commitment to timely delivery, competitive pricing, customization options, and ster satisfaction, we aim to build long-term relationships with our clients across various indbefrie Whether it is a small corporate requirement or large-scale bulk gifting, we ensure professional service and premium quality in every order. At Himalaya Crafts, we believe that a thoughtful gift strengthens professional relationships</p>
                </div>
            @endif

            <!--<div class="mt-8 grid grid-cols-2  gap-4">-->
            <!--    <div class="rounded-xl border border-neutral-100 bg-neutral-50 p-4">-->
            <!--        <i class="fa-solid fa-medal text-red-600"></i>-->
            <!--        <div class="mt-2 text-sm font-black text-neutral-950">Premium Quality</div>-->
            <!--        <p class="mt-1 text-xs leading-5 text-neutral-500">Handpicked products with superior finish and utility.</p>-->
            <!--    </div>-->
            <!--    <div class="rounded-xl border border-neutral-100 bg-neutral-50 p-4">-->
            <!--        <i class="fa-solid fa-handshake text-red-600"></i>-->
            <!--        <div class="mt-2 text-sm font-black text-neutral-950">Bulk Friendly</div>-->
            <!--        <p class="mt-1 text-xs leading-5 text-neutral-500">Quantity pricing for orders big and small across India.</p>-->
            <!--    </div>-->
            <!--</div>-->

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('bulk-quote.create') }}" class="inline-flex items-center gap-2 rounded-full bg-neutral-950 px-5 py-3 text-xs font-black uppercase tracking-wide text-white hover:bg-neutral-800">
                    <i class="fa-solid fa-file-invoice"></i> Request Bulk Quote
                </a>
                <a href="{{ $catalogueUrl }}" class="inline-flex items-center gap-2 rounded-full border border-neutral-200 bg-white px-5 py-3 text-xs font-black uppercase tracking-wide text-neutral-900 hover:border-neutral-900">
                    <i class="fa-solid fa-grid-2"></i> Browse Catalogue
                </a>
            </div>
        </article>

        <div class="grid grid-cols-2 px-3 gap-4">
            <div class="space-y-4">
                <div class="overflow-hidden rounded-2xl bg-neutral-100 aspect-[3/4]">
                    <img src="https://himalayacrafts.in/uploads/aboutpage.png"
                         alt="Corporate gifting" class="h-48 w-full object-cover" loading="lazy">
                </div>
                <div class="rounded-2xl bg-red-600 p-5 text-white">
                    <div class="text-3xl font-black">1000+</div>
                    <div class="mt-1 text-[10px] font-bold uppercase tracking-widest text-red-100">Catalogue Products</div>
                </div>
            </div>
            <div class="space-y-4 pt-10">
                <div class="rounded-2xl bg-neutral-950 p-5 text-white">
                    <div class="text-2xl font-black">PAN India</div>
                    <div class="mt-1 text-[10px] font-bold uppercase tracking-widest text-neutral-400">Bulk Supply</div>
                </div>
                <div class="overflow-hidden rounded-2xl bg-neutral-100 aspect-[3/4]">
                    <img src="https://himalayacrafts.in/uploads/aboutpage2.png"
                         alt="Premium gifts" class="h-48 w-full object-cover" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>
<hr>
{{-- ═══════════════ DIRECTOR'S MESSAGE ═══════════════ --}}

<section class="bg-white py-16 sm:py-20">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    <!-- Heading -->
    <div class="mb-10 text-center">
      <div class="text-xs font-black uppercase tracking-widest text-red-600">Leadership</div>
      <h2 class="mt-3 text-3xl font-black text-neutral-950 sm:text-4xl">
        Director&rsquo;s Message
      </h2>
    </div>

    <!-- Card -->
    <div class="overflow-hidden rounded-3xl bg-neutral-50 ring-1 ring-neutral-200/70 shadow-sm">
      <div class="flex flex-col md:flex-row">

        <!-- LEFT : Image (size controlled, kabhi bhi huge nahi hogi) -->
        <div class="flex w-full items-center justify-center p-6 md:w-2/5 md:p-8 lg:w-[38%]">
          <img src="https://himalayacrafts.in/uploads/dr.jpeg"
               alt="Jagtar Singh Vilku — Founder & Director"
               class="h-auto max-h-[420px] w-auto max-w-full object-contain"
               loading="lazy">
        </div>

        <!-- RIGHT : Saara text + signature -->
        <div class="w-full p-8 md:w-3/5 md:p-10 lg:w-[62%] lg:p-12">

          <h3 class="text-3xl font-black text-neutral-950 sm:text-4xl">
            Jagtar Singh Vilku
          </h3>

          <div class="mt-4">
            <span class="inline-block rounded-full bg-red-600 px-5 py-1.5 text-sm font-semibold text-white">
              Founder &amp; Director
            </span>
          </div>

          <div class="mt-6 h-1 w-16 rounded-full bg-red-600"></div>

          <!-- Greeting -->
          <p class="mt-6 text-lg font-semibold text-neutral-900">
            Dear Valued Clients,
          </p>

          <!-- Paragraphs -->
          <div class="mt-4 space-y-4 text-base leading-7 text-neutral-600">
            <p>
              At <span class="font-semibold text-neutral-900">Himalaya Crafts</span>, our vision is to
              provide premium and meaningful corporate gifting solutions that help organizations build
              stronger connections with employees, clients, and business partners.
            </p>
            <p>
              Since our inception, we have focused on quality products, exceptional service, and
              customer satisfaction. We understand that gifts represent appreciation, trust, and brand
              value.
            </p>
            <p>
              Our team continuously introduces innovative, stylish, and practical gifting options
              suitable for every occasion and budget.
            </p>
            <p>
              We are grateful for the trust of our clients and remain committed to delivering
              excellence, integrity, and dedication in everything we do.
            </p>
          </div>

          <!-- Quotes -->
          <div class="mt-6 rounded-2xl border-l-4 border-red-500 bg-white p-5">
            <p class="text-base font-semibold italic text-neutral-800">
              &ldquo;Crafted with Soul, Delivered with Care&rdquo;
            </p>
           
          </div>

          <!-- Signature -->
          <div class="mt-8 border-t border-neutral-200 pt-6">
            <p class="text-sm text-neutral-500">Warm Regards,</p>
            <div class="mt-2 text-3xl italic text-neutral-900"
                 style="font-family:'Brush Script MT','Lucida Handwriting',cursive;">
              Himalaya Crafts
            </div>
            <p class="mt-2 font-bold text-neutral-900">Jagtar Singh Vilku</p>
            <p class="text-sm font-semibold text-red-600">Founder &amp; Director</p>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>

{{-- ═══════════════ WHY CHOOSE US ═══════════════ --}}
<section class="bg-neutral-950 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <div class="text-xs font-black uppercase tracking-widest text-red-500">Why Himalaya Crafts</div>
            <h2 class="mt-3 text-3xl font-black text-white sm:text-4xl">What businesses count on us for.</h2>
            <p class="mt-4 text-base leading-7 text-neutral-400">From small bulk orders to large institutional gifting — our catalogue-first approach makes selection simple.</p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($values as $v)
            <div class="group relative rounded-2xl border border-neutral-800 bg-neutral-900 p-6 transition duration-300 hover:border-red-600">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-600/15 text-red-500">
                    <i class="fa-solid {{ $v['icon'] }}"></i>
                </div>
                <div class="mt-4 text-4xl font-black text-neutral-700 transition group-hover:text-red-600/30">{{ $v['n'] }}</div>
                <h3 class="mt-2 text-base font-black text-white">{{ $v['t'] }}</h3>
                <p class="mt-3 text-sm leading-7 text-neutral-400">{{ $v['d'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>




{{-- ═══════════════ WHAT WE DO ═══════════════ --}}
<!--<section class="bg-white py-16 sm:py-20">-->
<!--    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">-->
<!--        <div class="mx-auto max-w-2xl text-center">-->
<!--            <div class="text-xs font-black uppercase tracking-widest text-red-600">What We Do</div>-->
<!--            <h2 class="mt-3 text-3xl font-black text-neutral-950 sm:text-4xl">Supporting procurement teams with dependable choices.</h2>-->
<!--        </div>-->

<!--        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">-->
<!--            @foreach ($services as $s)-->
<!--            <div class="group rounded-2xl border border-neutral-100 bg-neutral-50 p-6 text-center transition duration-300 hover:-translate-y-1 hover:border-red-200 hover:bg-red-50 hover:shadow-md">-->
<!--                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 transition group-hover:bg-red-600 group-hover:text-white">-->
<!--                    <i class="fa-solid {{ $s['icon'] }} text-lg"></i>-->
<!--                </div>-->
<!--                <h3 class="mt-4 text-sm font-black text-neutral-950">{{ $s['t'] }}</h3>-->
<!--                <p class="mt-3 text-sm leading-7 text-neutral-500">{{ $s['d'] }}</p>-->
<!--            </div>-->
<!--            @endforeach-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->

{{-- CTA --}}
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-lg bg-neutral-950 px-6 py-8 text-white sm:px-8 lg:flex lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-black">Need bulk pricing or branding options?</h2>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-neutral-300">Share your product requirement, quantity and customization needs. Our team will respond with catalogue guidance and quote details.</p>
            </div>
            <a href="{{ route('bulk-quote.create') }}" class="mt-6 inline-flex rounded-full bg-neutral-950 px-5 py-3 text-sm font-black uppercase tracking-wide text-white hover:bg-neutral-800 lg:mt-0">Request Bulk Quote</a>
        </div>
    </div>
</section>

@else
{{-- ═══════════════ GENERIC PAGE ═══════════════ --}}
<section class="bg-neutral-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-neutral-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-red-600">Home</a>
            <span class="text-neutral-300">/</span>
            <span class="text-neutral-900">{{ $page->title }}</span>
        </nav>
        <div class="mt-8 max-w-3xl">
            <h1 class="text-3xl font-black text-neutral-950 sm:text-4xl lg:text-5xl">{{ $page->title }}</h1>
            @if ($page->meta_description)
                <p class="mt-5 text-base leading-8 text-neutral-600 sm:text-lg">{{ $page->meta_description }}</p>
            @endif
        </div>
    </div>
</section>
<section class="bg-white">
    <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        @if ($page->content)
            <article class="space-y-5 text-base leading-8 text-neutral-700 [&_a]:font-bold [&_a]:text-red-600 [&_h2]:pt-4 [&_h2]:text-2xl [&_h2]:font-black [&_h2]:text-neutral-950 [&_h3]:pt-3 [&_h3]:text-xl [&_h3]:font-black [&_h3]:text-neutral-950 [&_li]:ml-5 [&_li]:list-disc [&_ol_li]:list-decimal [&_strong]:text-neutral-950 [&_ul]:space-y-2">
                {!! $page->content !!}
            </article>
        @else
            <div class="rounded-lg border border-dashed border-neutral-200 bg-neutral-50 px-6 py-8 text-sm leading-7 text-neutral-500">
                This page will be updated soon.
            </div>
        @endif
    </div>
</section>
@endif

@endsection
