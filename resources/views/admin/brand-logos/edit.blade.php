@extends('admin.layouts.app')

@section('title', 'Edit Brand Logo')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Edit Brand Logo</h2>
        <p class="mt-1 text-sm text-gray-500">Update brand logo details.</p>
    </div>

    <a href="{{ route('admin.brand-logos.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Back</a>
</div>

<div class="max-w-3xl rounded border border-gray-200 bg-white p-6 shadow-sm">
    <form action="{{ route('admin.brand-logos.update', $brandLogo) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $brandLogo->name) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="sort_order" class="mb-1 block text-sm font-medium text-gray-700">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $brandLogo->sort_order) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('sort_order')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="link" class="mb-1 block text-sm font-medium text-gray-700">Link</label>
            <input type="text" id="link" name="link" value="{{ old('link', $brandLogo->link) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            @error('link')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="logo" class="mb-1 block text-sm font-medium text-gray-700">Logo</label>
            <img src="{{ asset($brandLogo->logo) }}" alt="{{ $brandLogo->name }}" class="mb-3 h-16 w-32 rounded object-contain">
            <input type="file" id="logo" name="logo" class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
            @error('logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $brandLogo->status)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
            <label for="status" class="text-sm font-medium text-gray-700">Active</label>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('admin.brand-logos.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">Update Brand Logo</button>
        </div>
    </form>
</div>
@endsection
