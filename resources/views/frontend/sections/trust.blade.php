@if ($trustItems->isNotEmpty())
    <section class="bg-red-50 py-14">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-2xl font-black text-neutral-950 md:text-3xl">
                Here's why you can trust us
            </h2>

            <div class="mt-9 grid gap-8 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($trustItems as $trustItem)
                    <div>
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-red-500 bg-white text-3xl text-red-600">
                            @if ($trustItem->image)
                                <img src="{{ asset($trustItem->image) }}" alt="{{ $trustItem->title }}" class="h-12 w-12 object-contain">
                            @elseif ($trustItem->icon)
                                <i class="{{ $trustItem->icon }}"></i>
                            @else
                                <i class="fa-solid fa-circle-check"></i>
                            @endif
                        </div>
                        <p class="mt-3 text-sm font-bold">{{ $trustItem->title }}</p>
                        @if ($trustItem->description)
                            <p class="mt-1 text-xs leading-5 text-neutral-600">{{ $trustItem->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
