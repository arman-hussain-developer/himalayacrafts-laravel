@extends('admin.layouts.app')

@section('title', 'Edit Menu Item')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Edit Menu Item</h2>
        <p class="mt-1 text-sm text-gray-500">Navigation Menus control header/footer links only. Page content is managed from CMS Pages.</p>
    </div>

    <a href="{{ route('admin.menus.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Back</a>
</div>

<div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        @include('admin.menus.partials.form')

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('admin.menus.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">Update Menu Item</button>
        </div>
    </form>
</div>
@endsection
