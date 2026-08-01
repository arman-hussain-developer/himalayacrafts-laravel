@extends('frontend.layouts.app')

@section('title', 'Cart')

@section('content')
<section class="border-b border-neutral-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-black">Shopping Cart</h1>
        <p class="mt-2 text-sm text-neutral-500">Review items and place a basic checkout order.</p>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1.4fr_1fr] lg:px-8">
    <div>
        @if (session('success'))
            <div class="mb-5 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        @if (! empty($cart))
            <form action="{{ route('cart.update') }}" method="POST" class="rounded border border-neutral-200 bg-white shadow-sm">
                @csrf

                <div class="divide-y divide-neutral-100">
                    @foreach ($cart as $itemKey => $item)
                        <div class="grid gap-4 p-4 sm:grid-cols-[96px_1fr_auto]">
                            <a href="{{ route('products.show', $item['slug']) }}">
                                @if ($item['image'])
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="h-24 w-24 rounded object-cover">
                                @else
                                    <div class="flex h-24 w-24 items-center justify-center rounded bg-neutral-100 text-xs text-neutral-400">No Image</div>
                                @endif
                            </a>

                            <div>
                                <a href="{{ route('products.show', $item['slug']) }}" class="font-bold text-neutral-950 hover:text-red-600">{{ $item['name'] }}</a>
                                @if ($item['variant_label'])
                                    <div class="mt-1 text-xs text-neutral-500">{{ $item['variant_label'] }}</div>
                                @endif
                                @if ($item['sku'])
                                    <div class="mt-1 text-xs text-neutral-500">SKU: {{ $item['sku'] }}</div>
                                @endif
                                <div class="mt-2 text-sm font-semibold">Rs. {{ number_format((float) $item['price'], 2) }}</div>
                            </div>

                            <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                                <input type="number" name="quantities[{{ $itemKey }}]" min="1" max="{{ $item['stock'] }}" value="{{ $item['quantity'] }}" class="w-20 rounded border border-neutral-300 px-3 py-2 text-sm">
                                <div class="text-sm font-bold">Rs. {{ number_format((float) ($item['price'] * $item['quantity']), 2) }}</div>
                                <button type="submit" form="remove-{{ md5($itemKey) }}" class="text-xs font-semibold text-red-600 hover:text-red-700">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end border-t border-neutral-100 p-4">
                    <button type="submit" class="rounded bg-neutral-900 px-5 py-2 text-sm font-bold text-white hover:bg-neutral-800">
                        Update Cart
                    </button>
                </div>
            </form>

            @foreach ($cart as $itemKey => $item)
                <form id="remove-{{ md5($itemKey) }}" action="{{ route('cart.remove', $itemKey) }}" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @else
            <div class="rounded border border-neutral-200 bg-white p-8 text-center shadow-sm">
                <div class="text-lg font-bold">Your cart is empty.</div>
                <a href="{{ route('products.index') }}" class="mt-4 inline-flex rounded bg-neutral-950 px-5 py-2 text-sm font-bold text-white hover:bg-neutral-800">
                    Shop Products
                </a>
            </div>
        @endif
    </div>

    <aside class="h-fit rounded border border-neutral-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-black">Checkout</h2>
        <div class="mt-4 flex items-center justify-between border-b border-neutral-100 pb-4 text-sm">
            <span>Total</span>
            <span class="text-lg font-black">Rs. {{ number_format((float) $cartTotal, 2) }}</span>
        </div>

        <form action="{{ route('cart.checkout') }}" method="POST" class="mt-5 space-y-4">
            @csrf

            <div>
                <label for="customer_name" class="mb-1 block text-sm font-medium text-neutral-700">Name</label>
                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            </div>

            <div>
                <label for="customer_email" class="mb-1 block text-sm font-medium text-neutral-700">Email</label>
                <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            </div>

            <div>
                <label for="customer_phone" class="mb-1 block text-sm font-medium text-neutral-700">Phone</label>
                <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            </div>

            <div>
                <label for="customer_address" class="mb-1 block text-sm font-medium text-neutral-700">Address</label>
                <textarea id="customer_address" name="customer_address" rows="4" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">{{ old('customer_address') }}</textarea>
            </div>

            <div class="grid gap-3 sm:grid-cols-3">
                <div>
                    <label for="city" class="mb-1 block text-sm font-medium text-neutral-700">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>
                <div>
                    <label for="state" class="mb-1 block text-sm font-medium text-neutral-700">State</label>
                    <input type="text" id="state" name="state" value="{{ old('state') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>
                <div>
                    <label for="pincode" class="mb-1 block text-sm font-medium text-neutral-700">Pincode</label>
                    <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>
            </div>

            <div>
                <label for="notes" class="mb-1 block text-sm font-medium text-neutral-700">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" @disabled(empty($cart)) class="w-full rounded bg-neutral-950 px-5 py-3 text-sm font-black text-white hover:bg-neutral-800 disabled:cursor-not-allowed disabled:bg-neutral-300">
                Place Order
            </button>
        </form>
    </aside>
</section>
@endsection
