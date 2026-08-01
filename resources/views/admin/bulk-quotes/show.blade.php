@extends('admin.layouts.app')

@section('title', 'Bulk Quote Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Bulk Quote #{{ $bulkQuote->id }}</h2>
        <p class="mt-1 text-sm text-gray-500">Received on {{ $bulkQuote->created_at?->format('d M Y, h:i A') }}</p>
    </div>

    <a href="{{ route('admin.bulk-quotes.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Back</a>
</div>

<div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
    <div class="space-y-6">
        <div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Customer Details</h3>
            <div class="mt-4 grid gap-4 text-sm text-gray-700 sm:grid-cols-2">
                <div><div class="text-xs font-semibold uppercase text-gray-500">Name</div><div class="mt-1">{{ $bulkQuote->name }}</div></div>
                <div><div class="text-xs font-semibold uppercase text-gray-500">Phone</div><div class="mt-1">{{ $bulkQuote->phone }}</div></div>
                <div><div class="text-xs font-semibold uppercase text-gray-500">Email</div><div class="mt-1">{{ $bulkQuote->email ?: 'N/A' }}</div></div>
                <div><div class="text-xs font-semibold uppercase text-gray-500">Company</div><div class="mt-1">{{ $bulkQuote->company_name ?: 'N/A' }}</div></div>
            </div>
        </div>

        <div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Requirement</h3>
            <div class="mt-4 grid gap-4 text-sm text-gray-700 sm:grid-cols-2">
                <div><div class="text-xs font-semibold uppercase text-gray-500">Category</div><div class="mt-1">{{ $bulkQuote->category?->name ?: 'N/A' }}</div></div>
                <div><div class="text-xs font-semibold uppercase text-gray-500">Quantity</div><div class="mt-1">{{ $bulkQuote->quantity ?: 'N/A' }}</div></div>
                <div class="sm:col-span-2">
                    <div class="text-xs font-semibold uppercase text-gray-500">Message</div>
                    <p class="mt-1 whitespace-pre-line leading-6">{{ $bulkQuote->requirement }}</p>
                </div>
            </div>
        </div>
    </div>

    <aside class="space-y-6">
        <div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Update Status</h3>
            <form action="{{ route('admin.bulk-quotes.update', $bulkQuote) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="status" class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($bulkQuote->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    Update Bulk Quote
                </button>
            </form>
        </div>

        <div class="rounded border border-red-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-red-700">Delete Request</h3>
            <form action="{{ route('admin.bulk-quotes.destroy', $bulkQuote) }}" method="POST" class="mt-4" onsubmit="return confirm('Delete this bulk quote request?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded border border-red-200 px-5 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                    Delete
                </button>
            </form>
        </div>
    </aside>
</div>
@endsection
