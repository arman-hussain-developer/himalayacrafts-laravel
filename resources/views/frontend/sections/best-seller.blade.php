<section class="bg-white py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-9 flex flex-col gap-4 text-center md:flex-row md:items-end md:justify-between md:text-left">
            <div>
                <h2 class="text-3xl font-black tracking-wide text-neutral-950 md:text-4xl">
                    Popular Catalog Picks
                </h2>
                <div class="mx-auto mt-3 h-1 w-12 rounded-full bg-red-600 md:mx-0"></div>
                <p class="mt-4 max-w-2xl text-sm leading-6 text-neutral-600">
                    Frequently requested products for corporate gifting, staff kits, promotional campaigns and office supply.
                </p>
            </div>

            <div class="hidden items-center gap-2 md:flex">
                <button type="button"
                        class="popular-prev flex h-10 w-10 items-center justify-center rounded-full bg-white text-neutral-700 shadow ring-1 ring-neutral-200 hover:text-red-600">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button type="button"
                        class="popular-next flex h-10 w-10 items-center justify-center rounded-full bg-white text-neutral-700 shadow ring-1 ring-neutral-200 hover:text-red-600">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>

        @if ($featuredProducts->isNotEmpty())
            <div class="popular-scroll scrollbar-hide flex gap-6 overflow-x-auto scroll-smooth pb-3">
                @foreach ($featuredProducts->take(12) as $product)
                    <div class="min-w-[260px] max-w-[260px] sm:min-w-[290px] sm:max-w-[290px] lg:min-w-[300px] lg:max-w-[300px]">
                        @include('frontend.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('products.index') }}"
                   class="inline-flex rounded-full border border-neutral-950 px-7 py-3 text-sm font-black uppercase tracking-wide text-neutral-950 transition hover:bg-neutral-950 hover:text-white">
                    View All Catalog
                </a>
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-neutral-300 p-10 text-center text-neutral-500">
                No featured products found. Mark products as featured from admin.
            </div>
        @endif
    </div>
</section>