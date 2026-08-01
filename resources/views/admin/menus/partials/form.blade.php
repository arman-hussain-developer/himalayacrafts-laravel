@php
    $selectedType = old('type', $menu->type ?? 'route');
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="title" class="mb-1 block text-sm font-medium text-gray-700">Admin Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $menu->title ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
        <p class="mt-1 text-xs text-gray-500">Internal name for admin users, for example Header Catalog.</p>
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="label" class="mb-1 block text-sm font-medium text-gray-700">Menu Label</label>
        <input type="text" id="label" name="label" value="{{ old('label', $menu->label ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
        <p class="mt-1 text-xs text-gray-500">Text shown on the website menu.</p>
        @error('label') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid gap-5 md:grid-cols-3">
    <div>
        <label for="type" class="mb-1 block text-sm font-medium text-gray-700">Type</label>
        <select id="type" name="type" data-menu-type class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            @foreach ($types as $value => $label)
                <option value="{{ $value }}" @selected($selectedType === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="location" class="mb-1 block text-sm font-medium text-gray-700">Location</label>
        <select id="location" name="location" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            @foreach ($locations as $location)
                <option value="{{ $location }}" @selected(old('location', $menu->location ?? 'header') === $location)>{{ ucfirst($location) }}</option>
            @endforeach
        </select>
        @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="sort_order" class="mb-1 block text-sm font-medium text-gray-700">Sort Order</label>
        <input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $menu->sort_order ?? 0) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
        @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid gap-5 md:grid-cols-2">
    <div data-type-panel="route">
        <label for="route_name" class="mb-1 block text-sm font-medium text-gray-700">Allowed Route</label>
        <select id="route_name" name="route_name" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            <option value="">Select route</option>
            @foreach ($allowedRoutes as $routeName => $routeLabel)
                <option value="{{ $routeName }}" @selected(old('route_name', $menu->route_name ?? '') === $routeName)>{{ $routeLabel }} ({{ $routeName }})</option>
            @endforeach
        </select>
        @error('route_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div data-type-panel="page">
        <label for="page_id" class="mb-1 block text-sm font-medium text-gray-700">CMS Page</label>
        <select id="page_id" name="page_id" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            <option value="">Select page</option>
            @foreach ($pages as $page)
                <option value="{{ $page->id }}" @selected((int) old('page_id', $menu->page_id ?? 0) === $page->id)>{{ $page->title }} (/page/{{ $page->slug }})</option>
            @endforeach
        </select>
        @error('page_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div data-type-panel="category">
        <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700">Category</label>
        <select id="category_id" name="category_id" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $menu->category_id ?? 0) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div data-type-panel="custom_url">
        <label for="url" class="mb-1 block text-sm font-medium text-gray-700">Custom URL</label>
        <input type="text" id="url" name="url" value="{{ old('url', $menu->url ?? '') }}" placeholder="/page/shipping-policy or https://example.com" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
        @error('url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="parent_id" class="mb-1 block text-sm font-medium text-gray-700">Parent Menu</label>
        <select id="parent_id" name="parent_id" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
            <option value="">Top level</option>
            @foreach ($parentMenus as $parentMenu)
                <option value="{{ $parentMenu->id }}" @selected((int) old('parent_id', $menu->parent_id ?? 0) === $parentMenu->id)>{{ ucfirst($parentMenu->location) }} / {{ $parentMenu->label }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">Use this to create dropdown items under a top-level menu.</p>
        @error('parent_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid gap-4 rounded border border-gray-200 bg-gray-50 p-4 sm:grid-cols-3">
    <label class="flex items-center gap-3 text-sm font-medium text-gray-700">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" name="status" value="1" @checked(old('status', $menu->status ?? true)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
        Active
    </label>

    <label class="flex items-center gap-3 text-sm font-medium text-gray-700">
        <input type="hidden" name="is_mega" value="0">
        <input type="checkbox" name="is_mega" value="1" @checked(old('is_mega', $menu->is_mega ?? false)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
        Mega menu
    </label>

    <label class="flex items-center gap-3 text-sm font-medium text-gray-700">
        <input type="hidden" name="open_in_new_tab" value="0">
        <input type="checkbox" name="open_in_new_tab" value="1" @checked(old('open_in_new_tab', $menu->open_in_new_tab ?? false)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
        Open in new tab
    </label>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.querySelector('[data-menu-type]');
        const panels = document.querySelectorAll('[data-type-panel]');

        function syncPanels() {
            const selectedType = typeSelect?.value || 'route';

            panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.typePanel !== selectedType);
            });
        }

        typeSelect?.addEventListener('change', syncPanels);
        syncPanels();
    });
</script>
@endpush
