@if ($brandLogos->isNotEmpty())
    <section class="bg-white py-12">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-2xl font-black text-neutral-950 md:text-3xl">Featured In</h2>

            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($brandLogos as $brandLogo)
                    @if ($brandLogo->link)
                        <a href="{{ $brandLogo->link }}" class="flex min-h-24 items-center justify-center rounded-xl border border-neutral-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <img src="{{ asset($brandLogo->logo) }}" alt="{{ $brandLogo->name }}" class="mx-auto h-16 max-w-[140px] object-contain">
                        </a>
                    @else
                        <div class="flex min-h-24 items-center justify-center rounded-xl border border-neutral-200 bg-white p-4 shadow-sm">
                            <img src="{{ asset($brandLogo->logo) }}" alt="{{ $brandLogo->name }}" class="mx-auto h-16 max-w-[140px] object-contain">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
