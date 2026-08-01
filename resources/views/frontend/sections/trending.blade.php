<section class="bg-neutral-50 py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col gap-4 text-center md:flex-row md:items-end md:justify-between md:text-left">
            <div>
                <h2 class="text-3xl font-black text-neutral-950 md:text-4xl">
                    Latest Catalogue Additions
                </h2>
                <div class="mx-auto mt-3 h-1 w-12 rounded-full bg-red-600 md:mx-0"></div>
                <p class="mt-4 max-w-2xl text-sm leading-6 text-neutral-600">
                    Fresh product options for bulk gifting, office use and promotional enquiries.
                </p>
            </div>

            <div class="hidden items-center gap-2 md:flex">
                <button type="button"
                        class="latest-prev flex h-10 w-10 items-center justify-center rounded-full bg-white text-neutral-700 shadow ring-1 ring-neutral-200 hover:text-red-600">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button type="button"
                        class="latest-next flex h-10 w-10 items-center justify-center rounded-full bg-white text-neutral-700 shadow ring-1 ring-neutral-200 hover:text-red-600">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>

        @if ($latestProducts->isNotEmpty())
            <div class="latest-scroll scrollbar-hide flex gap-6 overflow-x-auto scroll-smooth pb-3">
                @foreach ($latestProducts->take(12) as $product)
                    <div class="min-w-[260px] max-w-[260px] sm:min-w-[290px] sm:max-w-[290px] lg:min-w-[300px] lg:max-w-[300px]">
                        @include('frontend.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-neutral-300 bg-white p-10 text-center text-neutral-500">
                No latest products found.
            </div>
        @endif
    </div>
</section>