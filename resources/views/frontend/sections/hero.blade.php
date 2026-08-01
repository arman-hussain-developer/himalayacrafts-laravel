<section class="relative bg-white">
    @if ($banners->isNotEmpty())
        <div class="relative h-[320px] overflow-hidden sm:h-[430px] lg:h-[560px]" id="heroSlider">
            @foreach ($banners->take(5) as $index => $banner)
                <a href="{{ $banner->button_link ?: route('products.index') }}"
                   class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
                    <img src="{{ asset($banner->image) }}"
                         alt="{{ $banner->title ?? 'Banner' }}"
                         class="h-full w-full object-cover">

                    <div class="absolute inset-0 bg-gradient-to-r from-white/75 via-white/25 to-transparent"></div>

                    <div class="absolute inset-0 mx-auto flex max-w-7xl items-center px-4 sm:px-6 lg:px-8">
                        <div class="max-w-xl">
                            @if ($banner->subtitle)
                                <p class="mb-3 text-xs font-black uppercase tracking-[0.25em] text-red-600">
                                    {{ $banner->subtitle }}
                                </p>
                            @endif

                            <h1 class="text-2xl font-black leading-tight text-neutral-950 sm:text-5xl lg:text-2xl">
                                {{ $banner->title ?: 'Corporate gifts and catalogue products' }}
                            </h1>

                            <span class="mt-6 inline-flex rounded-full bg-neutral-950 px-7 py-3 text-sm font-bold text-white shadow-lg hover:bg-neutral-800">
                                {{ $banner->button_text ?: 'Request Enquiry' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach

            <button type="button" class="hero-prev absolute left-4 top-1/2 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-neutral-900 shadow hover:bg-white">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <button type="button" class="hero-next absolute right-4 top-1/2 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-neutral-900 shadow hover:bg-white">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div class="absolute bottom-5 left-0 right-0 z-30 flex justify-center gap-2">
                @foreach ($banners->take(5) as $index => $banner)
                    <button type="button" data-slide-dot="{{ $index }}" class="hero-dot h-2.5 w-2.5 rounded-full {{ $index === 0 ? 'bg-red-600' : 'bg-white/80' }}"></button>
                @endforeach
            </div>
        </div>
    @else
        <div class="flex h-[420px] items-center bg-neutral-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="max-w-2xl text-4xl font-black sm:text-2xl">
                    Corporate gifts, accessories and stationery for bulk enquiries.
                </h1>
                <a href="{{ route('products.index') }}" class="mt-6 inline-flex rounded-full bg-neutral-950 px-7 py-3 text-sm font-bold text-white">
                    Browse Catalog
                </a>
            </div>
        </div>
    @endif
</section>
