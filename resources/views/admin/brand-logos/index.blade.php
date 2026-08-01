@extends('admin.layouts.app')

@section('title', 'Brand Logos')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Brand Logos</h2>
        <p class="mt-1 text-sm text-gray-500">Manage featured in and brand logo strip.</p>
    </div>

    <a href="{{ route('admin.brand-logos.create') }}" class="inline-flex items-center justify-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
        Add Brand Logo
    </a>
</div>

<div class="overflow-hidden rounded border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Logo</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Link</th>
                    <th class="px-4 py-3">Sort</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($brandLogos as $brandLogo)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ asset($brandLogo->logo) }}" alt="{{ $brandLogo->name }}" class="h-12 w-24 rounded object-contain">
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $brandLogo->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $brandLogo->link ?: 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $brandLogo->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $brandLogo->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $brandLogo->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.brand-logos.edit', $brandLogo) }}" class="rounded border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">Edit</a>
                                <form action="{{ route('admin.brand-logos.destroy', $brandLogo) }}" method="POST" onsubmit="return confirm('Delete this brand logo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No brand logos found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $brandLogos->links() }}
</div>
@endsection
