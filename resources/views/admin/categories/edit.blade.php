@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Edit Category</h2>
        <p class="mt-1 text-sm text-gray-500">Update category details.</p>
    </div>

    <a href="{{ route('admin.categories.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        Back
    </a>
</div>

<div class="max-w-3xl rounded border border-gray-200 bg-white p-6 shadow-sm">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Description</label>
            <textarea data-wysiwyg="true" id="description" name="description" rows="4" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Image</label>
            @if ($category->image)
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="mb-3 h-20 w-20 rounded object-cover">
            @endif
            <input type="file" id="image" name="image" class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
            @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $category->status)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
            <label for="status" class="text-sm font-medium text-gray-700">Active</label>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('admin.categories.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection
