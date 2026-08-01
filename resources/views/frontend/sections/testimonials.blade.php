@if ($testimonials->isNotEmpty())
    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-9 flex flex-col gap-4 text-center md:flex-row md:items-end md:justify-between md:text-left">
                <div>
                    <h2 class="text-3xl font-black text-neutral-950 md:text-4xl">
                        What Clients Say
                    </h2>
                    <div class="mx-auto mt-3 h-1 w-12 rounded-full bg-red-600 md:mx-0"></div>
                    <p class="mt-4 max-w-2xl text-sm leading-6 text-neutral-600">
                        Feedback from buyers, dealers and corporate gifting customers.
                    </p>
                </div>

                <div class="hidden items-center gap-2 md:flex">
                    <button type="button"
                            class="testimonial-prev flex h-10 w-10 items-center justify-center rounded-full bg-white text-neutral-700 shadow ring-1 ring-neutral-200 hover:text-red-600">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button"
                            class="testimonial-next flex h-10 w-10 items-center justify-center rounded-full bg-white text-neutral-700 shadow ring-1 ring-neutral-200 hover:text-red-600">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="testimonial-scroll scrollbar-hide flex gap-6 overflow-x-auto scroll-smooth pb-3">
                @foreach ($testimonials as $testimonial)
                    <div class="min-w-[280px] max-w-[280px] sm:min-w-[340px] sm:max-w-[340px] lg:min-w-[380px] lg:max-w-[380px]">
                        <div class="h-full rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                            <div class="mb-5 flex items-center gap-4">
                                @if ($testimonial->image)
                                    <img src="{{ asset($testimonial->image) }}"
                                         alt="{{ $testimonial->name }}"
                                         class="h-14 w-14 rounded-full object-cover ring-2 ring-red-100">
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-xl font-black text-red-600">
                                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div class="text-left">
                                    <div class="font-black text-neutral-950">{{ $testimonial->name }}</div>

                                    @if ($testimonial->designation)
                                        <div class="mt-1 text-xs font-medium text-neutral-500">
                                            {{ $testimonial->designation }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4 text-sm text-yellow-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $testimonial->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                @endfor
                            </div>

                            <p class="text-sm leading-7 text-neutral-600">
                                “{{ $testimonial->message }}”
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endif