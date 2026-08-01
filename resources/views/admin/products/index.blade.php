@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Products</h2>
        <p class="mt-1 text-sm text-gray-500">Manage catalog products and inventory basics.</p>
    </div>

    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
        Add Product
    </a>
</div>

<form action="{{ route('admin.products.index') }}" method="GET" class="mb-5 rounded border border-gray-200 bg-white p-4 shadow-sm">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
        <div class="xl:col-span-2">
            <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
            <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Product name or SKU" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
        </div>

        <div>
            <label for="category_id" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Category</label>
            <select id="category_id" name="category_id" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="sub_category_id" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Sub Category</label>
            <select id="sub_category_id" name="sub_category_id" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                <option value="">All Sub Categories</option>
                @foreach ($subCategories as $subCategory)
                    <option value="{{ $subCategory->id }}" @selected((string) request('sub_category_id') === (string) $subCategory->id)>
                        {{ $subCategory->category?->name }} / {{ $subCategory->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
            <select id="status" name="status" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                <option value="">All</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>
        </div>

        <div>
            <label for="is_featured" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Featured</label>
            <select id="is_featured" name="is_featured" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                <option value="">All</option>
                <option value="1" @selected(request('is_featured') === '1')>Yes</option>
                <option value="0" @selected(request('is_featured') === '0')>No</option>
            </select>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap justify-end gap-3">
        <a href="{{ route('admin.products.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Reset
        </a>
        <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
            Apply Filter
        </button>
    </div>
</form>

<div class="overflow-hidden rounded border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Featured</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            @if ($product->featured_image)
                                <img src="{{ asset($product->featured_image) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover">
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded bg-gray-100 text-xs text-gray-400">N/A</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                            <div class="mt-1 text-xs text-gray-500">{{ $product->sku ?: 'No SKU' }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            <div>{{ $product->category?->name ?? 'N/A' }}</div>
                            <div class="mt-1 text-xs text-gray-400">{{ $product->subCategory?->name ?? 'No sub category' }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            @if ((float) ($product->sale_price ?: $product->price) > 0)
                                <div>{{ number_format((float) ($product->sale_price ?: $product->price), 2) }}</div>
                            @else
                                <div class="text-xs font-medium text-gray-500">Price on enquiry</div>
                            @endif
                            @if ($product->sale_price && (float) $product->price > 0)
                                <div class="mt-1 text-xs text-green-700">Sale {{ number_format((float) $product->sale_price, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $product->stock }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $product->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $product->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $product->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="rounded border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $products->links() }}
</div>
@endsection
