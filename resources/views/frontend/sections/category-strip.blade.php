<section class="bg-white py-7">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="relative">
            <button type="button"
                    class="cat-strip-prev absolute left-0 top-1/2 z-20 hidden h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-700 shadow ring-1 ring-neutral-200 hover:text-red-600 md:flex">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>

            <div class="cat-strip-scroll scrollbar-hide flex gap-7 overflow-x-auto scroll-smooth px-1 pb-2 md:px-10">
                @foreach ($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}"
                       class="group flex min-w-[105px] max-w-[105px] flex-col items-center">

                        <div class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-red-50 to-white shadow-sm ring-1 ring-red-100 transition duration-300 group-hover:-translate-y-1 group-hover:shadow-lg group-hover:ring-red-300">
                            @if ($category->image)
                                <img src="{{ asset($category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="h-full w-full object-cover">
                            @else
                                <i class="fa-solid fa-layer-group text-xl text-red-500"></i>
                            @endif
                        </div>

                        <span class="mt-3 line-clamp-2 text-center text-[11px] font-black uppercase leading-4 tracking-wide text-neutral-800 transition group-hover:text-red-600">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>

            <button type="button"
                    class="cat-strip-next absolute right-0 top-1/2 z-20 hidden h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-700 shadow ring-1 ring-neutral-200 hover:text-red-600 md:flex">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>
        </div>

    </div>
</section>