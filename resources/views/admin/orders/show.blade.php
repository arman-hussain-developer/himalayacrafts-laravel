@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Order {{ $order->order_no ?: 'ORD-' . $order->id }}</h2>
        <p class="mt-1 text-sm text-gray-500">Placed on {{ $order->created_at?->format('d M Y, h:i A') }}</p>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Back</a>
</div>

<div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
    <div class="space-y-6">
        <div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Customer Details</h3>
            <div class="mt-4 grid gap-4 text-sm text-gray-700 sm:grid-cols-2">
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500">Name</div>
                    <div class="mt-1">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500">Phone</div>
                    <div class="mt-1">{{ $order->customer_phone }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500">Email</div>
                    <div class="mt-1">{{ $order->customer_email ?: 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500">User</div>
                    <div class="mt-1">{{ $order->user?->name ?? 'Guest' }}</div>
                </div>
                <div class="sm:col-span-2">
                    <div class="text-xs font-semibold uppercase text-gray-500">Shipping Address</div>
                    <div class="mt-1 leading-6">
                        {{ $order->customer_address }}
                        @if ($order->city || $order->state || $order->pincode)
                            <br>{{ collect([$order->city, $order->state, $order->pincode])->filter()->join(', ') }}
                        @endif
                    </div>
                </div>
                @if ($order->notes)
                    <div class="sm:col-span-2">
                        <div class="text-xs font-semibold uppercase text-gray-500">Notes</div>
                        <div class="mt-1 leading-6">{{ $order->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-lg font-semibold">Order Items</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Variant</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $item->product_name ?: $item->product?->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->variant_label ?: 'Default' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ number_format((float) $item->price, 2) }}</td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">{{ number_format((float) $item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <aside class="space-y-6">
        <div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Update Status</h3>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="status" class="mb-1 block text-sm font-medium text-gray-700">Order Status</label>
                    <select id="status" name="status" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
                        @foreach ($orderStatuses as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="payment_status" class="mb-1 block text-sm font-medium text-gray-700">Payment Status</label>
                    <select id="payment_status" name="payment_status" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
                        @foreach ($paymentStatuses as $status)
                            <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    @error('payment_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    Update Order
                </button>
            </form>
        </div>

        <div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Totals</h3>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format((float) $order->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span>Shipping</span><span>{{ number_format((float) $order->shipping_amount, 2) }}</span></div>
                <div class="flex justify-between"><span>Discount</span><span>{{ number_format((float) $order->discount_amount, 2) }}</span></div>
                <div class="flex justify-between border-t border-gray-100 pt-3 text-base font-semibold">
                    <span>Total</span>
                    <span>{{ number_format((float) $order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </aside>
</div>
@endsection
