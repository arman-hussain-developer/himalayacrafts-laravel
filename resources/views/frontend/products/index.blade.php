@extends('frontend.layouts.app')

@section('title', $selectedSubCategory?->name ?: ($selectedCategory?->name ?: 'Products / Catalog'))
@section('meta_description', $selectedSubCategory ? 'Browse ' . $selectedSubCategory->name . ' catalogue products and send bulk enquiries.' : ($selectedCategory ? 'Browse ' . $selectedCategory->name . ' products for corporate and bulk enquiries.' : 'Browse corporate gifts, accessories and stationery catalogue products.'))

@section('content')
<section class="border-b border-neutral-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-neutral-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-red-600">Home</a>
            <span class="text-neutral-300">/</span>
            <span class="text-neutral-900">Products</span>
        </nav>

        <div class="mt-7 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.24em] text-red-600">Corporate Catalogue</p>
                <h1 class="mt-3 text-3xl font-black text-neutral-950 md:text-5xl">
                    {{ $selectedSubCategory?->name ?: ($selectedCategory?->name ?: 'Products / Catalog') }}
                </h1>
                @if ($selectedSubCategory || $selectedCategory || request('q'))
                    <p class="mt-2 text-sm font-semibold text-neutral-500">
                        {{ $selectedSubCategory ? $selectedCategory?->name . ' › ' . $selectedSubCategory->name : ($selectedCategory?->name ?: 'Search Results') }}
                    </p>
                @endif
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('bulk-quote.create') }}" class="bg-neutral-950 px-5 py-3 text-xs font-black uppercase tracking-wide text-white hover:bg-neutral-800">Request Bulk Quote</a>
                <a href="{{ \App\Support\WhatsApp::bulkQuoteUrl() }}" target="_blank" rel="noopener" class="border border-green-600 px-5 py-3 text-xs font-black uppercase tracking-wide text-green-700 hover:bg-green-50">WhatsApp Us</a>
            </div>
        </div>

        <p class="mt-5 max-w-3xl text-sm leading-7 text-neutral-600">
            @if ($selectedSubCategory)
                Showing products in <strong>{{ $selectedSubCategory->name }}</strong> — send enquiries for current pricing and availability.
            @elseif ($selectedCategory)
                Showing products in <strong>{{ $selectedCategory->name }}</strong> for corporate gifting and promotional supply.
            @elseif (request('q'))
                Search results for &ldquo;{{ request('q') }}&rdquo;.
            @else
                Browse active catalogue products and send enquiries for current availability and bulk pricing.
            @endif
        </p>
    </div>
</section>

<section class="bg-neutral-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Mobile filter accordion --}}
        <details class="mb-5 border border-neutral-200 bg-white p-4 shadow-sm lg:hidden" id="mobile-filters">
            <summary class="flex cursor-pointer list-none items-center justify-between text-sm font-bold text-neutral-900">
                <span><i class="fa-solid fa-sliders mr-2 text-neutral-400"></i>Filters &amp; Sort</span>
                <i class="fa-solid fa-chevron-down text-xs text-neutral-400 transition-transform"></i>
            </summary>
            <div class="mt-4">
                @include('frontend.products.partials.filters', ['categories' => $categories])
            </div>
        </details>

        <div class="grid gap-8 lg:grid-cols-[280px_1fr]">

            {{-- Desktop sidebar --}}
            <aside class="hidden lg:block">
                <div class="sticky top-36 border border-neutral-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between border-b border-neutral-100 pb-4">
                        <h2 class="text-base font-black text-neutral-950">Refine Catalog</h2>
                        @if (request()->hasAny(['q', 'category', 'sub_category', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}"
                               class="text-xs font-semibold text-red-700 hover:underline">Clear all</a>
                        @endif
                    </div>
                    <p class="mb-4 text-xs leading-5 text-neutral-500">Filter by category, sub-category, budget or search by name / SKU.</p>
                    @include('frontend.products.partials.filters', ['categories' => $categories])
                </div>
            </aside>

            {{-- Product grid --}}
            <div>
                {{-- Active filter chips --}}
                @if (request()->hasAny(['q', 'category', 'sub_category', 'min_price', 'max_price', 'sort']))
                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        @foreach ([
                            'q'            => ['label' => 'Search',       'icon' => 'fa-magnifying-glass'],
                            'category'     => ['label' => 'Category',     'icon' => 'fa-tag'],
                            'sub_category' => ['label' => 'Sub-category', 'icon' => 'fa-tags'],
                            'min_price'    => ['label' => 'Min budget',   'icon' => 'fa-indian-rupee-sign'],
                            'max_price'    => ['label' => 'Max budget',   'icon' => 'fa-indian-rupee-sign'],
                            'sort'         => ['label' => 'Sort',         'icon' => 'fa-arrow-up-wide-short'],
                        ] as $key => $meta)
                            @if (request($key))
                                @php
                                    $clearUrl = request()->fullUrlWithoutQuery([$key]);
                                @endphp
                                <a href="{{ $clearUrl }}"
                                   class="inline-flex items-center gap-1.5 border border-red-100 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 hover:bg-red-100">
                                    <i class="fa-solid {{ $meta['icon'] }} text-[9px]"></i>
                                    {{ $meta['label'] }}: {{ request($key) }}
                                    <i class="fa-solid fa-xmark ml-0.5 text-[9px]"></i>
                                </a>
                            @endif
                        @endforeach
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-1 border border-neutral-200 bg-white px-2.5 py-1 text-xs font-semibold text-neutral-600 hover:bg-neutral-50">
                            <i class="fa-solid fa-xmark text-[9px]"></i> Clear all
                        </a>
                    </div>
                @endif

                {{-- Result count + sort (quick sort on mobile without sidebar) --}}
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-neutral-600">
                        <span class="font-black text-neutral-900">{{ $products->total() }}</span> products found
                    </p>
                    {{-- Quick sort select (visible on all screens; mirrors the sidebar sort) --}}
                    <form action="{{ route('products.index') }}" method="GET" id="quick-sort-form" class="flex items-center gap-2">
                        @foreach (request()->except('sort', 'page') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <label for="quick_sort" class="text-xs font-semibold text-neutral-500">Sort:</label>
                        <select id="quick_sort" name="sort"
                                onchange="this.form.submit()"
                                class="border border-neutral-300 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 focus:border-red-500 focus:outline-none">
                            <option value="latest"         @selected(request('sort', 'latest') === 'latest')>Latest</option>
                            <option value="price_low_high" @selected(request('sort') === 'price_low_high')>Price: Low → High</option>
                            <option value="price_high_low" @selected(request('sort') === 'price_high_low')>Price: High → Low</option>
                            <option value="featured"       @selected(request('sort') === 'featured')>Featured first</option>
                        </select>
                    </form>
                </div>

                {{-- Product grid --}}
                <div class="grid grid-cols-2 gap-3 sm:gap-5 xl:grid-cols-3">
                    @forelse ($products as $product)
                        @include('frontend.partials.product-card', ['product' => $product])
                    @empty
                        <div class="border border-neutral-200 bg-white p-8 text-center xl:col-span-3">
                            <i class="fa-regular fa-folder-open mb-3 text-3xl text-neutral-300"></i>
                            <p class="text-sm font-semibold text-neutral-600">No products matched your filters.</p>
                            <a href="{{ route('products.index') }}" class="mt-3 inline-block text-sm font-bold text-red-700 hover:underline">Clear filters</a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    /*
     * Dynamic sub-category filtering.
     * When the category dropdown changes, hide sub-categories that don't
     * belong to the selected category. Works for both mobile and desktop forms.
     */
    @php
        $subCatMap = [];
        foreach ($categories as $cat) {
            foreach ($cat->subCategories as $sub) {
                $subCatMap[$sub->slug] = $cat->slug;
            }
        }
    @endphp
    const subCatMap = @json($subCatMap);

    document.querySelectorAll('select[name="category"]').forEach((catSelect) => {
        const form      = catSelect.closest('form');
        const subSelect = form?.querySelector('select[name="sub_category"]');
        if (!subSelect) return;

        const allOptions = Array.from(subSelect.options).map((o) => ({
            value: o.value,
            text:  o.text,
            cat:   subCatMap[o.value] || null,
        }));

        function filterSubs() {
            const chosen = catSelect.value;
            subSelect.innerHTML = '';
            const blank = document.createElement('option');
            blank.value = '';
            blank.textContent = 'All Sub Categories';
            subSelect.appendChild(blank);

            allOptions.filter((o) => !o.value || !chosen || o.cat === chosen).forEach((o) => {
                const el = document.createElement('option');
                el.value = o.value;
                el.textContent = o.text;
                if (o.value === @json(request('sub_category'))) el.selected = true;
                subSelect.appendChild(el);
            });
        }

        filterSubs();
        catSelect.addEventListener('change', () => {
            filterSubs();
            form.submit();
        });
        subSelect.addEventListener('change', () => form.submit());
    });

    /* Close mobile filter accordion after submit */
    document.querySelectorAll('#mobile-filters form').forEach((f) => {
        f.addEventListener('submit', () => {
            document.getElementById('mobile-filters')?.removeAttribute('open');
        });
    });
})();
</script>
@endpush
