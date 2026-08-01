@extends('admin.layouts.app')

@section('title', 'Edit Testimonial')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Edit Testimonial</h2>
        <p class="mt-1 text-sm text-gray-500">Update customer review details.</p>
    </div>

    <a href="{{ route('admin.testimonials.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Back</a>
</div>

<div class="max-w-3xl rounded border border-gray-200 bg-white p-6 shadow-sm">
    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $testimonial->name) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="designation" class="mb-1 block text-sm font-medium text-gray-700">Designation</label>
                <input type="text" id="designation" name="designation" value="{{ old('designation', $testimonial->designation) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('designation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="message" class="mb-1 block text-sm font-medium text-gray-700">Message</label>
                <textarea id="message" name="message" rows="5" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">{{ old('message', $testimonial->message) }}</textarea>
            @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="rating" class="mb-1 block text-sm font-medium text-gray-700">Rating</label>
                <input type="number" id="rating" name="rating" min="1" max="5" value="{{ old('rating', $testimonial->rating) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('rating')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="sort_order" class="mb-1 block text-sm font-medium text-gray-700">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $testimonial->sort_order) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('sort_order')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Image</label>
            @if ($testimonial->image)
                <img src="{{ asset($testimonial->image) }}" alt="{{ $testimonial->name }}" class="mb-3 h-16 w-16 rounded-full object-cover">
            @endif
            <input type="file" id="image" name="image" class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
            @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $testimonial->status)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
            <label for="status" class="text-sm font-medium text-gray-700">Active</label>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('admin.testimonials.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">Update Testimonial</button>
        </div>
    </form>
</div>
@endsection
