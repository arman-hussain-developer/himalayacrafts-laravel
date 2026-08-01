
{{-- Exclusive Category --}}
<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-black uppercase tracking-wide text-neutral-950 md:text-4xl">
                Featured Categories
            </h2>
            <div class="mx-auto mt-3 h-1 w-12 rounded-full bg-red-600"></div>
        </div>

        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($categories->take(4) as $category)
                <a href="{{ route('products.category', $category->slug) }}"
                   class="group rounded-3xl border-2 border-red-500 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                    <div class="relative overflow-hidden rounded-2xl bg-red-50">
                        @if ($category->image)
                            <img src="{{ asset($category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="h-56 w-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-56 items-center justify-center bg-red-50">
                                <i class="fa-solid fa-layer-group text-5xl text-red-500"></i>
                            </div>
                        @endif

                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/50 to-transparent p-4">
                            <div class="text-center text-2xl font-black uppercase leading-none text-white">
                                {{ $category->name }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 rounded-full bg-neutral-950 px-4 py-3 text-center text-xs font-black uppercase tracking-wider text-white transition group-hover:bg-neutral-800">
                        {{ $category->name }}
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-neutral-300 p-10 text-center text-neutral-500">
                    No categories found.
                </div>
            @endforelse
        </div>
    </div>
</section>
