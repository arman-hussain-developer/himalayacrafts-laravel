@extends('admin.layouts.app')

@section('title', 'Sub Categories')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Sub Categories</h2>
        <p class="mt-1 text-sm text-gray-500">Manage category-specific collections.</p>
    </div>

    <a href="{{ route('admin.sub-categories.create') }}" class="inline-flex items-center justify-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
        Add Sub Category
    </a>
</div>

<div class="overflow-hidden rounded border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($subCategories as $subCategory)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $subCategory->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $subCategory->category?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $subCategory->slug }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $subCategory->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $subCategory->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.sub-categories.edit', $subCategory) }}" class="rounded border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    Edit
                                </a>
                                <form action="{{ route('admin.sub-categories.destroy', $subCategory) }}" method="POST" onsubmit="return confirm('Delete this sub category?')">
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
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No sub categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $subCategories->links() }}
</div>
@endsection
