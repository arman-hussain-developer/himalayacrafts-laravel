@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold">Dashboard</h2>
    <p class="mt-1 text-sm text-gray-500">Store content overview.</p>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Categories</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['categories'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Sub Categories</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['subCategories'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Products</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['products'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Banners</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['banners'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Pages</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['pages'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Testimonials</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['testimonials'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Trust Items</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['trustItems'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Brand Logos</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['brandLogos'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Orders</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['orders'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Pending Orders</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['pendingOrders'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Delivered Orders</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['completedOrders'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Revenue</div>
        <div class="mt-3 text-3xl font-semibold">{{ number_format((float) $counts['totalRevenue'], 2) }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">New Enquiries</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['newEnquiries'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Enquiries</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['totalEnquiries'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">New Bulk Quotes</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['newBulkQuotes'] }}</div>
    </div>

    <div class="rounded border border-gray-200 bg-white p-5 shadow-sm">
        <div class="text-sm font-medium text-gray-500">Total Bulk Quotes</div>
        <div class="mt-3 text-3xl font-semibold">{{ $counts['totalBulkQuotes'] }}</div>
    </div>
</div>
@endsection
