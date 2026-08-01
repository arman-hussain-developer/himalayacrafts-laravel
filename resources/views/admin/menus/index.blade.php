@extends('admin.layouts.app')

@section('title', 'Navigation Menus')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Navigation Menus</h2>
        <p class="mt-1 text-sm text-gray-500">Navigation Menus control header/footer links only. Page content is managed from CMS Pages.</p>
    </div>

    <a href="{{ route('admin.menus.create') }}" class="inline-flex items-center justify-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
        Add Menu Item
    </a>
</div>

<form method="GET" class="mb-5 grid gap-3 rounded border border-gray-200 bg-white p-4 shadow-sm md:grid-cols-5">
    <div class="md:col-span-2">
        <label for="search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Label, title, route or URL" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
    </div>

    <div>
        <label for="location" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Location</label>
        <select id="location" name="location" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            <option value="">All</option>
            @foreach (\App\Models\NavigationMenu::LOCATIONS as $location)
                <option value="{{ $location }}" @selected(request('location') === $location)>{{ ucfirst($location) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="type" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Type</label>
        <select id="type" name="type" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            <option value="">All</option>
            <option value="route" @selected(request('type') === 'route')>Route</option>
            <option value="page" @selected(request('type') === 'page')>Page</option>
            <option value="category" @selected(request('type') === 'category')>Category</option>
            <option value="custom_url" @selected(request('type') === 'custom_url')>Custom URL</option>
        </select>
    </div>

    <div>
        <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
        <div class="flex gap-2">
            <select id="status" name="status" class="min-w-0 flex-1 rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                <option value="">All</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>
            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">Filter</button>
        </div>
    </div>
</form>

<div class="overflow-hidden rounded border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Label</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Target</th>
                    <th class="px-4 py-3">Location</th>
                    <th class="px-4 py-3">Parent</th>
                    <th class="px-4 py-3">Sort</th>
                    <th class="px-4 py-3">Flags</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($menus as $menu)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $menu->label }}</div>
                            <div class="text-xs text-gray-500">{{ $menu->title }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ str($menu->type)->replace('_', ' ')->title() }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            @if ($menu->type === 'route')
                                {{ $menu->route_name ?: 'N/A' }}
                            @elseif ($menu->type === 'page')
                                {{ $menu->page?->title ?: 'Missing page' }}
                            @elseif ($menu->type === 'category')
                                {{ $menu->category?->name ?: 'Missing category' }}
                            @else
                                {{ $menu->url ?: 'N/A' }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ ucfirst($menu->location) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $menu->parent?->label ?: 'Top level' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $menu->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @if ($menu->is_mega)
                                    <span class="rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">Mega</span>
                                @endif
                                @if ($menu->open_in_new_tab)
                                    <span class="rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700">New tab</span>
                                @endif
                                @if (! $menu->is_mega && ! $menu->open_in_new_tab)
                                    <span class="text-xs text-gray-400">None</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $menu->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $menu->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="rounded border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">Edit</a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Delete this menu item? Child items will become top-level items.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">No menu items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $menus->links() }}
</div>
@endsection
