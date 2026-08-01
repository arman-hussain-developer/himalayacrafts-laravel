@php
    $whatsappUrl = \App\Support\WhatsApp::productUrl($product->name);
    $productUrl  = route('products.show', $product->slug);
    $enquiryUrl  = $productUrl . '#product-enquiry';
    $label       = $product->subCategory?->name ?: $product->category?->name;
@endphp

<article class="group flex h-full flex-col overflow-hidden border border-neutral-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-neutral-300 hover:shadow-lg">

    {{-- Product image --}}
    <a href="{{ $productUrl }}" class="block border-b border-neutral-100 bg-neutral-50" tabindex="-1" aria-hidden="true">
        <div class="aspect-[4/3] w-full overflow-hidden p-4">
            @if ($product->featured_image)
                <img src="{{ asset($product->featured_image) }}"
                     alt="{{ $product->name }}"
                     loading="lazy"
                     class="h-full w-full object-contain transition duration-300 group-hover:scale-[1.04]">
            @else
                <img src="{{ asset('uploads/ui/category-default.svg') }}"
                     alt="{{ $product->name }}"
                     loading="lazy"
                     class="h-full w-full object-contain opacity-50">
            @endif
        </div>
    </a>

    {{-- Card body --}}
    <div class="flex flex-1 flex-col p-3">

        {{-- Category label + Featured badge --}}
        <div class="mb-1.5 flex min-h-5 items-center gap-2">
            @if ($label)
                <span class="truncate text-[10px] font-bold uppercase tracking-widest text-neutral-400">{{ $label }}</span>
            @endif
            @if ($product->is_featured)
                <span class="ml-auto shrink-0 border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-700">Featured</span>
            @endif
        </div>

        {{-- Product name --}}
        <a href="{{ $productUrl }}"
           class="line-clamp-2 min-h-[2.5rem] text-[15px] font-bold leading-snug text-neutral-950 hover:text-red-700">
            {{ $product->name }}
        </a>

        {{-- SKU badge --}}
        @if ($product->sku)
            <div class="mt-1.5">
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-neutral-400">
                    <i class="fa-solid fa-barcode text-[9px]"></i>
                    {{ $product->sku }}
                </span>
            </div>
        @endif

        {{-- Color dot swatches (colors relation is eager-loaded in both listing controllers) --}}
        @if (isset($product->colors) && $product->colors->isNotEmpty())
            <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                @foreach ($product->colors->take(7) as $color)
                    @php
                        $valid = $color->color_code
                            && preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $color->color_code);
                    @endphp
                    <span class="h-4 w-4 rounded-full border border-white shadow ring-1 ring-neutral-200 transition group-hover:ring-neutral-300"
                          style="background-color:{{ $valid ? $color->color_code : '#e5e7eb' }}"
                          title="{{ $color->color_name }}"></span>
                @endforeach
                @if ($product->colors->count() > 7)
                    <span class="text-[10px] font-semibold text-neutral-400">+{{ $product->colors->count() - 7 }}</span>
                @endif
            </div>
        @endif

        {{-- Price indicator --}}
        <div class="mt-2 text-xs font-semibold uppercase tracking-wide text-neutral-400">
            Price on enquiry
        </div>

        {{-- CTA buttons (push to bottom) --}}
        <div class="mt-auto grid grid-cols-2 gap-2 pt-4">
            <a href="{{ $enquiryUrl }}"
               class="bg-neutral-950 px-2 py-2 text-center text-xs font-bold uppercase tracking-wide text-white transition hover:bg-neutral-800">
                <!--Send-->
                Enquiry
            </a>
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
               class="border border-green-600 px-1 py-2 text-center text-xs font-bold uppercase tracking-wide text-green-700 transition hover:bg-green-50" style="
    font-size: 11px;
">WhatsApp
                <!--<i class="fa-brands fa-whatsapp mr-1"></i>WhatsApp-->
            </a>
        </div>

    </div>
</article>
