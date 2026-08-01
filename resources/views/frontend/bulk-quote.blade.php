@extends('frontend.layouts.app')

@section('title', 'Bulk Quote')
@section('meta_description', 'Request a bulk quote for corporate gifts, bags, bottles, diaries, pens and promotional catalogue products.')

@section('content')
<section class="relative overflow-hidden bg-neutral-950">
    <img src="{{ asset('uploads/ui/bulk-quote-banner.svg') }}" alt="Bulk corporate gifts catalogue" class="absolute inset-0 h-full w-full object-cover opacity-50">
    <div class="absolute inset-0 bg-gradient-to-r from-neutral-950 via-neutral-950/85 to-neutral-950/30"></div>

    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
        <div class="flex flex-col justify-center text-white">
            <p class="text-xs font-black uppercase tracking-[0.28em] text-red-300">Corporate Supply Desk</p>
            <h1 class="mt-4 text-4xl font-black leading-tight md:text-5xl">Request a Bulk Quote</h1>
            <p class="mt-5 max-w-xl text-sm leading-7 text-white/80">
                Tell us your quantity, category and branding needs. We will respond with availability, options and commercial pricing for corporate gifts and catalogue products.
            </p>
            <div class="mt-8 flex flex-wrap gap-3 text-xs font-bold uppercase tracking-wide text-white/80">
                <span class="rounded-full border border-white/20 px-4 py-2">Logo Branding</span>
                <span class="rounded-full border border-white/20 px-4 py-2">Bulk Supply</span>
                <span class="rounded-full border border-white/20 px-4 py-2">Fast Follow-up</span>
            </div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-2xl sm:p-7">
            @if (session('success'))
                <div class="mb-5 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    Please fix the highlighted fields and try again.
                </div>
            @endif

            <form action="{{ route('bulk-quote.store') }}" method="POST" class="grid gap-4">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-semibold text-neutral-800">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="mb-1 block text-sm font-semibold text-neutral-800">Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="email" class="mb-1 block text-sm font-semibold text-neutral-800">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="company_name" class="mb-1 block text-sm font-semibold text-neutral-800">Company Name</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        @error('company_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="category_id" class="mb-1 block text-sm font-semibold text-neutral-800">Product Category</label>
                        <select id="category_id" name="category_id" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="quantity" class="mb-1 block text-sm font-semibold text-neutral-800">Approx Quantity</label>
                        <input type="number" min="1" id="quantity" name="quantity" value="{{ old('quantity') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        @error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="requirement" class="mb-1 block text-sm font-semibold text-neutral-800">Requirement</label>
                    <textarea id="requirement" name="requirement" rows="5" placeholder="Example: 500 steel bottles with logo branding for employee gifting." class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">{{ old('requirement') }}</textarea>
                    @error('requirement')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="rounded bg-neutral-950 px-6 py-3 text-sm font-black text-white hover:bg-neutral-800">
                    Request Bulk Quote
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
