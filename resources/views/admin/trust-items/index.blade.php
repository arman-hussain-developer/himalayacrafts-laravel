@extends('admin.layouts.app')

@section('title', 'Trust Items')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Trust Items</h2>
        <p class="mt-1 text-sm text-gray-500">Manage homepage trust badges.</p>
    </div>

    <a href="{{ route('admin.trust-items.create') }}" class="inline-flex items-center justify-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
        Add Trust Item
    </a>
</div>

<div class="overflow-hidden rounded border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Visual</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Sort</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($trustItems as $trustItem)
                    <tr>
                        <td class="px-4 py-3">
                            @if ($trustItem->image)
                                <img src="{{ asset($trustItem->image) }}" alt="{{ $trustItem->title }}" class="h-12 w-12 rounded object-cover">
                            @elseif ($trustItem->icon)
                                <div class="flex h-12 w-12 items-center justify-center rounded bg-gray-100 text-gray-600">
                                    <i class="{{ $trustItem->icon }}"></i>
                                </div>
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded bg-gray-100 text-xs text-gray-400">N/A</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $trustItem->title }}</td>
                        <td class="max-w-md px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($trustItem->description, 90) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $trustItem->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $trustItem->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $trustItem->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.trust-items.edit', $trustItem) }}" class="rounded border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">Edit</a>
                                <form action="{{ route('admin.trust-items.destroy', $trustItem) }}" method="POST" onsubmit="return confirm('Delete this trust item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No trust items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $trustItems->links() }}
</div>
@endsection
