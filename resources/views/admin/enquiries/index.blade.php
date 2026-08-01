@extends('admin.layouts.app')

@section('title', 'Product Enquiries')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Product Enquiries</h2>
        <p class="mt-1 text-sm text-gray-500">Track customer catalogue enquiries and follow-up status.</p>
    </div>

    <a href="{{ route('admin.enquiries.export', request()->query()) }}" class="inline-flex items-center justify-center rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        Export CSV
    </a>
</div>

<form action="{{ route('admin.enquiries.index') }}" method="GET" class="mb-5 grid gap-3 rounded border border-gray-200 bg-white p-4 shadow-sm md:grid-cols-5">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search product, name, phone, email" class="rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">

    <select name="status" class="rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
        <option value="">All Statuses</option>
        @foreach ($statuses as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>

    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">

    <button type="submit" class="rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">
        Filter
    </button>
</form>

<div class="overflow-hidden rounded border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Customer Name</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3 text-right">View</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($enquiries as $enquiry)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">#{{ $enquiry->id }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $enquiry->product_name ?: $enquiry->product?->name ?: 'General enquiry' }}</div>
                            @if ($enquiry->variant)
                                <div class="mt-1 text-xs text-gray-500">{{ $enquiry->variant->variant_name }}: {{ $enquiry->variant->variant_value }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $enquiry->customer_name }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $enquiry->customer_phone }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $enquiry->customer_email ?: 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium
                                {{ $enquiry->status === 'new' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $enquiry->status === 'contacted' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $enquiry->status === 'converted' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $enquiry->status === 'closed' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ ucfirst($enquiry->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $enquiry->created_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="rounded border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">No product enquiries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $enquiries->links() }}
</div>
@endsection
