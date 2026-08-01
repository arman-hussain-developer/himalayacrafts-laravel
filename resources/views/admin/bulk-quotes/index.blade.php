@extends('admin.layouts.app')

@section('title', 'Bulk Quotes')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Bulk Quotes</h2>
        <p class="mt-1 text-sm text-gray-500">Manage corporate and bulk catalogue quote requests.</p>
    </div>

    <a href="{{ route('admin.bulk-quotes.export', request()->query()) }}" class="inline-flex items-center justify-center rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        Export CSV
    </a>
</div>

<form action="{{ route('admin.bulk-quotes.index') }}" method="GET" class="mb-5 grid gap-3 rounded border border-gray-200 bg-white p-4 shadow-sm md:grid-cols-5">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, phone, company" class="rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">

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
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Company</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Quantity</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3 text-right">View</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($bulkQuotes as $bulkQuote)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $bulkQuote->name }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $bulkQuote->phone }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $bulkQuote->company_name ?: 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $bulkQuote->category?->name ?: 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $bulkQuote->quantity ?: 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">{{ ucfirst($bulkQuote->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $bulkQuote->created_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.bulk-quotes.show', $bulkQuote) }}" class="rounded border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">No bulk quote requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $bulkQuotes->links() }}
</div>
@endsection
