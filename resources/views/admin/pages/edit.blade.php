@extends('admin.layouts.app')

@section('title', 'Edit CMS Page')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Edit CMS Page</h2>
        <p class="mt-1 text-sm text-gray-500">CMS Pages manage content such as About Us, Shipping Policy, Returns Policy and Terms. To show a page in header/footer, add it from Navigation Menus.</p>
    </div>

    <a href="{{ route('admin.pages.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        Back
    </a>
</div>

<div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="title" class="mb-1 block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                <p class="mt-1 text-xs text-gray-500">Public page heading, for example About Us, Shipping Policy, Returns Policy, Privacy Policy, or Terms & Conditions.</p>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="mb-1 block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                <p class="mt-1 text-xs text-gray-500">Controls the public URL: /page/{{ old('slug', $page->slug) ?: '{slug}' }}.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="content" class="mb-1 block text-sm font-medium text-gray-700">Content</label>
            <textarea data-wysiwyg="true" id="content" name="content" rows="10" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">{{ old('content', $page->content) }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Content appears on the public CMS page. Use CKEditor formatting for headings, links, lists and paragraphs.</p>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700">Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                <p class="mt-1 text-xs text-gray-500">SEO/browser title only. It should not be written as frontend page content.</p>
                @error('meta_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="3" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">{{ old('meta_description', $page->meta_description) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">SEO summary only. It may be used as the page subtitle, but not as body content.</p>
                @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-3">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $page->status)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
            <label for="status" class="text-sm font-medium text-gray-700">Active</label>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('admin.pages.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Update Page
            </button>
        </div>
    </form>
</div>
@endsection
