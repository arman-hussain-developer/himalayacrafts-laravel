@if (($homeSectionSettings->get('promo_status') ?? '0') === '1')
    @php
        $promoImage = $homeSectionSettings->get('promo_background_image');
        $promoColor = $homeSectionSettings->get('promo_background_color') ?: 'red';
    @endphp

    <section class="relative overflow-hidden py-10 text-white" style="background-color: {{ $promoColor }};">
        @if ($promoImage)
            <img src="{{ asset($promoImage) }}" alt="{{ $homeSectionSettings->get('promo_title') ?: 'Promo' }}" class="absolute inset-0 h-full w-full object-cover opacity-35">
            <div class="absolute inset-0 bg-black/25"></div>
        @endif

        <div class="relative mx-auto max-w-7xl px-4 text-center">
            <h2 class="text-3xl font-black">{{ $homeSectionSettings->get('promo_title') ?: 'Special Offer' }}</h2>

            @if ($homeSectionSettings->get('promo_subtitle'))
                <p class="mt-2 text-sm">{{ $homeSectionSettings->get('promo_subtitle') }}</p>
            @endif

            @if ($homeSectionSettings->get('promo_button_text'))
                <a href="{{ $homeSectionSettings->get('promo_button_link') ?: route('products.index') }}"
                   class="mt-5 inline-block rounded-full bg-white px-6 py-3 text-sm font-bold text-red-600">
                    {{ $homeSectionSettings->get('promo_button_text') }}
                </a>
            @endif
        </div>
    </section>
@endif
