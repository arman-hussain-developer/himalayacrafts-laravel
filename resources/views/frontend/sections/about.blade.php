@if (($homeSectionSettings->get('about_status') ?? '0') === '1')
    @php
        $aboutImage = $homeSectionSettings->get('about_background_image');
    @endphp

    <section id="about" class="relative overflow-hidden bg-neutral-950">
        <div class="absolute inset-0">
            @if ($aboutImage)
                <img src="{{ asset($aboutImage) }}" class="h-full w-full object-cover opacity-45" alt="{{ $homeSectionSettings->get('about_title') ?: 'About' }}">
            @else
                <img src="{{ asset('uploads/ui/about-catalog-banner.svg') }}" class="h-full w-full object-cover opacity-45" alt="{{ $homeSectionSettings->get('about_title') ?: 'About' }}">
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/60 to-black/30"></div>
        </div>

        <div class="relative mx-auto grid min-h-[480px] max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div></div>

            <div class="text-white">
                <h2 class="text-3xl font-black md:text-4xl">{{ $homeSectionSettings->get('about_title') ?: 'About Us' }}</h2>
                <div class="mt-4 h-px w-full bg-white/40"></div>

                @if ($homeSectionSettings->get('about_description'))
                    <div class="mt-5 text-sm font-medium leading-7 text-white/90">
                        {!! $homeSectionSettings->get('about_description') !!}
                    </div>
                @endif

                @if ($homeSectionSettings->get('about_button_text'))
                    <a href="{{ $homeSectionSettings->get('about_button_link') ?: route('contact') }}"
                       class="mt-7 inline-flex border border-white px-6 py-3 text-sm font-black uppercase tracking-wide text-white transition hover:bg-white hover:text-neutral-950">
                        {{ $homeSectionSettings->get('about_button_text') }}
                    </a>
                @endif
            </div>
        </div>
    </section>
@endif
